		<div class="modal fade" id="guide-modal" tabindex="-1" role="dialog" aria-labelledby="RootXS Modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">{{ $strModalTitle or 'Welcome!' }}</h4>
					</div>
					<div class="modal-body" data-token="<?php echo csrf_token(); ?>">
						@include($strModalContent, ['aData' => isset($aData) ? $aData : [], 'iStep' => isset($iStep) ? $iStep : 0])
					</div>
					<div class="modal-footer">
						<div class="col-xs-5">
							<button class="guide-modal-nav prev btn btn-default pull-left" type="button"></button>
						</div>
						<div class="col-xs-2 text-center">
							<span id="guide-loading" style="display:none;">Loading...</span>
							<span id="guide-error" style="display:none;">Error!</span>
						</div>
						<div class="col-xs-5">
							<button class="guide-modal-nav next btn btn-info pull-right" type="button"></button>
							{!! Form::hidden('guide-step', 0, ['id' => 'guide-step']) !!}
						</div>
					</div>
				</div>
			</div>
		</div>
