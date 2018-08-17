<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Mittwald.Typo3Forum',
    'Pi1',
    'typo3_forum'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Mittwald.Typo3Forum',
    'Widget',
    'typo3_forum Widgets'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'typo3_forum'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript/Bootstrap',
    'typo3_forum Bootstrap Template'
);

$pluginSignature = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature . '_pi1'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature . '_pi1',
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Pi1.xml'
);

$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature . '_widget'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature . '_widget',
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Widgets.xml'
);
