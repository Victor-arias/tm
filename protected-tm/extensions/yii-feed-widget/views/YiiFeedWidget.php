<?php
/**
 * FileDoc: 
 * View file for YiiFeedWidget.
 * This extension depends on both idna convert and Simple Pie php libraries
 *  
 * PHP version 5.3
 * 
 * @category Extensions
 * @package  YiiFeedWidget
 * @author   Richard Walker <richie@mediasuite.co.nz>
 * @license  BSD License http://www.opensource.org/licenses/bsd-license.php
 * @link     http://mediasuite.co.nz
 * @see      simplepie.org
 * @see      http://www.phpclasses.org/browse/file/5845.html
 * 
 */ 
?>
<?php //$this->registerClientScript('yii-feeds-widget.js'); ?>
<?php //$this->registerClientCSS('yii-feeds-widget.css'); ?>
<?php echo CHtml::hiddenField('yii-feeds-widget-url', $url); ?>
<?php echo CHtml::hiddenField('yii-feeds-widget-limit', $limit); ?>
<?php echo CHtml::hiddenField('yii-feeds-widget-layout', $layout); ?>
<?php 
echo CHtml::hiddenField(
    'yii-feeds-widget-action-url',  
    Yii::app()->createUrl('YiiFeedWidget/getFeed')
); 
?>
<!-- Container to hold feed items -->
<div id="yii-feed-container" class="noticias">
    <?php echo CHtml::image($this->getSpinner()); ?>
</div>