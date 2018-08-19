@extends('template')
@set('title', 'TENTANG | ')

@content('isi')
	<b>App Name :</b> {{app_name}}
	<br><b>App Vers :</b> {{app_vers}}
	<br><b>App Author :</b> {{app_author}}
	<br><b>App Company :</b> {{app_company}}
@endcontent