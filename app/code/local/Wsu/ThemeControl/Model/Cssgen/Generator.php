<?php
class Wsu_ThemeControl_Model_Cssgen_Generator extends Mage_Core_Model_Abstract {
    public function __construct() {
        parent::__construct();
    }
    public function generateCss($x0b, $x0c, $x0d) {
        if ($x0c) {
            if ($x0d) {
                $this->_generateStoreCss($x0b, $x0d);
            } else {
                $this->_generateWebsiteCss($x0b, $x0c);
            }
        } else {
            $x0e = Mage::app()->getWebsites(false, true);
            foreach ($x0e as $x0f => $x10) {
                $this->_generateWebsiteCss($x0b, $x0f);
            }
        }
    }
    protected function _generateWebsiteCss($x0b, $x0c) {
        $x10 = Mage::app()->getWebsite($x0c);
        foreach ($x10->getStoreCodes() as $x0f) {
            $this->_generateStoreCss($x0b, $x0f);
        }
    }
    protected function _generateStoreCss($x0b, $x0d) {
        if (!Mage::app()->getStore($x0d)->getIsActive())
            return;
        $x11 = '_' . $x0d;
        $x12 = $x0b . $x11 . '.css';
        $x13 = Mage::helper('wsu_themecontrol/cssgen')->getGeneratedCssDir() . $x12;
        $x14 = 'wsu/themecontrol/css/' . $x0b . '.phtml';
        Mage::register('cssgen_store', $x0d);
        try {
            $x15 = Mage::app()->getLayout()->createBlock("c\x6f\x72\145\x2f\164\x65\x6d\160late")->setData('area', 'frontend')->setTemplate($x14)->toHtml();
            if (empty($x15)) {
                throw new Exception(Mage::helper('wsu_themecontrol')->__("Tem\x70\x6c\x61\x74e \146i\154e\x20\151\163\040\145m\160\164y \x6f\x72\x20\144\x6f\x65\x73\x6e't\x20ex\151\163\x74: \045\x73", $x14));
            }
            $x16 = new Varien_Io_File();
            $x16->setAllowCreateFolders(true);
            $x16->open(array(
                'path' => Mage::helper('wsu_themecontrol/cssgen')->getGeneratedCssDir()
            ));
            $x16->streamOpen($x13, 'w+');
            $x16->streamLock(true);
            $x16->streamWrite($x15);
            $x16->streamUnlock();
            $x16->streamClose();
        }
        catch (Exception $x17) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('wsu_themecontrol')->__('Failed generating CSS file: %s in %s', $x12, Mage::helper('wsu_themecontrol/cssgen')->getGeneratedCssDir()) . '<br/>Message: ' . $x17->getMessage());
            Mage::logException($x17);
        }
        Mage::unregister('cssgen_store');
    }
}