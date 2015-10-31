	<p>
		<small>
			You are receiving this email because you requested it from
			{!! HTML::link('/', 'rootxs.org') !!}.
			To stop receiving emails, please
			@if (isset($strEncryptedEmail) && $strEncryptedEmail)
			{!! HTML::link(
				'unsubscribe?i=' . $strEncryptedEmail,
				'unsubscribe here'
			) !!}.
			@else
			update your preferences at {!! HTML::link('profile') !!}
			@endif
		</small>
	</p>
