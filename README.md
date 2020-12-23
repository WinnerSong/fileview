# 开源文件预览 api 使用 sdk
- 提交 sdk 初衷为方便各团队成员快速接入使用  
通过传参数获取文件预览链接、多图片预览链接、提前入列链接

- 使用说明  
```$xslt
$host = 'https://xxx.xxx.com';
$onlineFileview = new OnlineFileview($host);
//获取文件在线预览链接 demo
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
$fileOriginUrl = 'https://xxx.xxx.oss-cn-shenzhen.aliyuncs.com/test/下载工具.pdf';
$appendParams = [
    'cors' => 'disabled', 'officePreviewType' => 'pdf',
    'pdfDownloadDisable' => true, 'watermarkTxt' => 'water'
];
$onlinePreviewLink = $onlineFileview->getOnlinePreviewLink($fileOriginUrl, $appendParams);
echo "在线预览链接=" . $onlinePreviewLink . "\n";

//多图片预览
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
$currentPictureUrl = 'https://szwwinter.oss-cn-shenzhen.aliyuncs.com/test/1.jpg';
$picturesUrl = ['https://xxx.xxx.oss-cn-shenzhen.aliyuncs.com/test/2.jpg', 'https://xxx.xxx.oss-cn-shenzhen.aliyuncs.com/test/1.jpg'];
$appendParams = ['watermarkTxt' => 'water'];
$picturesPreviewLink = $onlineFileview->getPicturesPreviewLink($currentPictureUrl, $picturesUrl, $appendParams);
echo "多图片预览链接=" . $picturesPreviewLink . "\n";

//提前入列
/**
 * 获取入列转码链接
 * @param $fileOriginUrl 原始文件链接地址
 * @param array $appendParams 可选参数
 * @param bool $urlEncode
 * @return string
 */
$fileOriginUrl = 'https://xxx.xxx.oss-cn-shenzhen.aliyuncs.com/演示文稿1.pptx';
$addTaskLink = $onlineFileview->getAddTaskLink($fileOriginUrl, []);
echo "提前入列链接=" . $addTaskLink . "\n";
```