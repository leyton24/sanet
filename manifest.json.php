<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

define('BX_MANIFEST', true);

require_once('./inc/header.inc.php');

$aContent = [
    'name' => '',
    'short_name' => '',
    'description' => getParam('sys_pwa_manifest_description'),
    'orientation' => 'portrait',
    'start_url' => parse_url(BX_DOL_URL_ROOT, PHP_URL_PATH),
    'display' => 'standalone',
    'scope' => '/',
    'background_color' => getParam('sys_pwa_manifest_background_color'),
    'theme_color' => getParam('sys_pwa_manifest_theme_color'),
    'gcm_sender_id' => isLogged() ? '482941778795' : ''
];

$aContent['name'] = ($sName = getParam('sys_pwa_manifest_name')) != '' ? $sName : parse_url(BX_DOL_URL_ROOT, PHP_URL_HOST);
$aContent['short_name'] = ($sShortName = getParam('sys_pwa_manifest_short_name')) != '' ? $sShortName : $aContent['name'];

foreach($aContent as $sKey => $sValue)
    if(empty($sValue))
        unset($aContent[$sKey]);

$aAdi = [];

/*
 * Apple device icons
 */
if(($iId = (int)getParam('sys_site_icon_apple')) != 0) {
    $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_APPLE);

    $sSizes = '180x180';
    if(($aTranscoderParams = $oTranscoder->getFilterParams('Resize')) !== false)
        $sSizes = $aTranscoderParams['w'] . 'x' . $aTranscoderParams['h'];

    $aAdi[] = [
        'src' => $oTranscoder->getFileUrl($iId), 
        'type' => $oTranscoder->getFileMimeType($iId), 
        'sizes' => $sSizes
    ];
}

/*
 * Android device icons
 */
if(($iId = (int)getParam('sys_site_icon_android')) != 0) {
    $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_ANDROID);

    $sSizes = '192x192';
    if(($aTranscoderParams = $oTranscoder->getFilterParams('Resize')) !== false)
        $sSizes = $aTranscoderParams['w'] . 'x' . $aTranscoderParams['h'];

    $aAdi[] = [
        'src' => $oTranscoder->getFileUrl($iId), 
        'type' => $oTranscoder->getFileMimeType($iId), 
        'sizes' => $sSizes
    ];
}

if(($iId = (int)getParam('sys_site_icon_android_splash')) != 0) {
    $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_ANDROID_SPLASH);

    $sSizes = '512x512';
    if(($aTranscoderParams = $oTranscoder->getFilterParams('Resize')) !== false)
        $sSizes = $aTranscoderParams['w'] . 'x' . $aTranscoderParams['h'];

    $aAdi[] = [
        'src' => $oTranscoder->getFileUrl($iId), 
        'type' => $oTranscoder->getFileMimeType($iId), 
        'sizes' => $sSizes
    ];
}

if(!empty($aAdi) && is_array($aAdi))
    $aContent['icons'] = $aAdi;

header("Content-Type: application/json");
header("X-Robots-Tag: none");
echo json_encode($aContent);
?>
