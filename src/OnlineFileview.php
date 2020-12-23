<?php
/**
 * Created by PhpStorm.
 * User: songzw
 * Date: 2020/12/22
 * Time: 23:10
 */
namespace imy\fileview;


/**
 * 生成预览服务链接
 * Class OnlineFileview
 * @package imy\fileview
 */
class OnlineFileview
{
    /**
     * 预览服务站点域名
     * @var null|string
     */
    private $host = null;

    /**
     * 文件预览路径
     * @var string
     */
    private $onlinePreviewPath = '/onlinePreview';

    /**
     * 多图片预览路径
     * @var string
     */
    private $picturesPreviewPath = '/picturesPreview';

    /**
     * 文件提前入列路径
     * @var string
     */
    private $addTaskPath = '/addTask';

    /**
     * OnlineFileview constructor.
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = rtrim($host, '/');
    }

    /**
     * 获取文件预览地址
     * appendParams 参数可选值如下：
     * officePreviewType 文件预览格式，对 pdf、doc、ppt、dwg 文件有效，可选值 image、pdf，默认 image
     * pdfDownloadDisable 是否允许 pdf 文件下载，对 pdf、doc、ppt、dwg 文件使用 pdf 格式预览时有效，默认为 false
     * cors 文件原始链接是否允许跨域，仅对 pdf 文件预览生效，当原始链接不允许跨域访问时需要设置为 disabled
     * watermarkTxt 预览水印，对所有文件有效，默认没有水印
     * @param $fileOriginUrl 原始文件链接地址
     * @param array $appendParams 可选参数
     * @param bool $urlEncode 是否对参数编码
     * @return string
     */
    public function getOnlinePreviewLink($fileOriginUrl, $appendParams = [], $urlEncode = true)
    {
        $appendParams = array_merge(['url' => $fileOriginUrl], $appendParams);
        return $this->getLink($this->onlinePreviewPath, $appendParams, $urlEncode);
    }


    /**
     * 获取多图片预览地址
     * appendParams 参数可选值如下：
     * watermarkTxt 预览水印，对所有文件有效，默认没有水印
     * @param $currentPictureUrl 默认打开的图片链接地址
     * @param $picturesUrl 所有预览的图片链接地址
     * @param array $appendParams 可选参数
     * @param bool $urlEncode 是否对参数编码
     * @return string
     */
    public function getPicturesPreviewLink($currentPictureUrl, $picturesUrl, $appendParams = [], $urlEncode = true)
    {
        $appendParams = array_merge(['currentUrl' => $currentPictureUrl, 'urls' => implode('|', $picturesUrl)], $appendParams);
        return $this->getLink($this->picturesPreviewPath, $appendParams, $urlEncode);
    }

    /**
     * 获取入列转码链接
     * @param $fileOriginUrl 原始文件链接地址
     * @param array $appendParams 可选参数
     * @param bool $urlEncode
     * @return string
     */
    public function getAddTaskLink($fileOriginUrl, $appendParams = [], $urlEncode = true)
    {
        $appendParams = array_merge(['url' => $fileOriginUrl], $appendParams);
        return $this->getLink($this->addTaskPath, $appendParams, $urlEncode);
    }

    /**
     * url中的参数值进行 urlencode
     * @param $params
     * @param bool $urlEncode
     * @return string
     */
    private function buildQueryParams(&$params, $urlEncode = true)
    {
        if ($urlEncode) {
            return http_build_query($params);
        } else {
            $query = '';
            array_walk($params, function ($value, $key) use (&$query) {
                $query .= "{$key}={$value}&";
            });
            return rtrim($query, '&');
        }
    }

    /**
     * 生成链接
     * @param $path
     * @param $appendParams
     * @param $urlEncode
     * @return string
     */
    private function getLink($path, $appendParams, $urlEncode)
    {
        return sprintf("%s%s?%s", $this->host, $path, $this->buildQueryParams($appendParams, $urlEncode));
    }

}