<?php

	// no direct access
	defined('UNLIMITED_ADDONS_INC') or die;

	class UniteCreatorProviderMasterView{
		
		/**
		 * construct
		 */
		public function __construct(){
			
			$this->putHtml();
			
		}
		
		
		/**
		 * put image select dialog
		 */
		private function putImageSelectDialog(){
			
			$objAssets = new UniteCreatorAssetsWork();
			$objAssets->initByKey("image_browser");
			$objAssets->setOption(UniteCreatorAssets::OPTION_ID, "uc_dialogimage_browser");
			
			?>
			
			<div id="uc_dialog_image_select" class="uc-dialog-image-select unite-inputs" style="display:none"> 
				
				<div class="uc-dialog-image-select-inner">
					
					<?php $objAssets->putHTML(null, true);?>
									
				</div>
				
				<div class="uc-dialog-image-select-bottom">
					
					<?php _e("Selected Image: ", UNLIMITED_ADDONS_TEXTDOMAIN)?>
					
					<input id="uc_dialog_image_select_url" type="text" readonly class="unite-input-regular"  value="">
					
					<div class="vert_sap10"></div>
					
					<a id="uc_dialog_image_select_button" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Select Image",UNLIMITED_ADDONS_TEXTDOMAIN)?></a>
				
				</div>
				
			</div>
			
			<?php 
		}

		
		/**
		 * put image select dialog
		 */
		private function putAudioSelectDialog(){
		
			$objAssets = new UniteCreatorAssetsWork();
			$objAssets->initByKey("audio_browser");
			$objAssets->setOption(UniteCreatorAssets::OPTION_ID, "uc_dialogaudio_browser");
			
			?>
			
			<div id="uc_dialog_audio_select" class="uc-dialog-image-select unite-inputs" style="display:none"> 
				
				<div class="uc-dialog-image-select-inner">
					
					<?php $objAssets->putHTML(null, true);?>
									
				</div>
				
				<div class="uc-dialog-image-select-bottom">
					
					<?php _e("Selected Audio: ", ADDONLIBRARY_TEXTDOMAIN)?>
					
					<input id="uc_dialog_audio_select_url" type="text" readonly class="unite-input-regular"  value="">
					
					<div class="vert_sap10"></div>
					
					<a id="uc_dialog_audio_select_button" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Select Audio",ADDONLIBRARY_TEXTDOMAIN)?></a>
				
				</div>
				
			</div>
			
			<?php 
		}
		
		
		/**
		 * put html
		 */
		private function putHtml(){
			
			$this->putImageSelectDialog();
			
			$this->putAudioSelectDialog();
		}
		
		
	}

	$uc_providerMasterView = new UniteCreatorProviderMasterView();
	
?>