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
    //对文件url进行decode，多次decode 对 url不影响
    const MAX_DECODE_TIMES = 3;
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
     * switchButton 是否展示image和pdf切换按钮，默认展示，当传值为disabled时，不展示
     * cors 文件原始链接是否允许跨域，仅对 pdf 文件预览生效，当原始链接不允许跨域访问时需要设置为 disabled
     * watermarkTxt 预览水印，对所有文件有效，默认没有水印
     * @param $fileOriginUrl 原始文件链接地址
     * @param array $appendParams 可选参数
     * @return string
     */
    public function getOnlinePreviewLink($fileOriginUrl, $appendParams = [])
    {
        $appendParams = array_merge(['url' => $fileOriginUrl], $appendParams);
        return $this->getLink($this->onlinePreviewPath, $appendParams);
    }


    /**
     * 获取多图片预览地址
     * appendParams 参数可选值如下：
     * watermarkTxt 预览水印，对所有文件有效，默认没有水印
     * @param $currentPictureUrl 默认打开的图片链接地址
     * @param $picturesUrl 所有预览的图片链接地址
     * @param array $appendParams 可选参数
     * @return string
     */
    public function getPicturesPreviewLink($currentPictureUrl, $picturesUrl, $appendParams = [])
    {
        $appendParams = array_merge(['currentUrl' => $currentPictureUrl, 'urls' => $picturesUrl], $appendParams);
        return $this->getLink($this->picturesPreviewPath, $appendParams);
    }

    /**
     * 获取入列转码链接
     * @param $fileOriginUrl 原始文件链接地址
     * @param array $appendParams 可选参数
     * @return string
     */
    public function getAddTaskLink($fileOriginUrl, $appendParams = [])
    {
        $appendParams = array_merge(['url' => $fileOriginUrl], $appendParams);
        return $this->getLink($this->addTaskPath, $appendParams);
    }

    /**
     * url中的参数值进行 urlencode
     * @param $params
     * @return string
     */
    private function buildQueryParams(&$params)
    {
        if (!empty($params['url'])) {
            $params['url'] = $this->getEncodeUrl($params['url']);
        }

        if (!empty($params['currentUrl'])) {
            $params['currentUrl'] = $this->getEncodeUrl($params['currentUrl']);
        }

        if (!empty($params['urls'])) {
            $urls = [];
            foreach ($params['urls'] as $url) {
                array_push($urls, $this->getEncodeUrl($url));
            }

            $params['urls'] = implode('|', $urls);
        }
        return http_build_query($params);
    }

    /**
     * 对文件url进行多次decode，再进行encode，防止对访问地址进行多次 encode
     * @param $originUrl
     * @return string
     */
    private function getEncodeUrl($originUrl)
    {
        for ($i=0; $i< OnlineFileview::MAX_DECODE_TIMES; $i++) {
            $originUrl = urldecode($originUrl);
        }

        return urlencode($originUrl);
    }

    /**
     * 生成链接
     * @param $path
     * @param $appendParams
     * @return string
     */
    private function getLink($path, $appendParams)
    {
        return sprintf("%s%s?%s", $this->host, $path, $this->buildQueryParams($appendParams));
    }
}
