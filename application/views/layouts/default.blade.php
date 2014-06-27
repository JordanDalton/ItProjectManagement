<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ isSet($title) ? $title : '' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    {{ Asset::container('bootstrapper')->styles() }}
    {{ HTML::style('css/screen.css') }}

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body id="{{ Request::route()->controller }}" class="{{ Request::route()->controller_action }}">

    {{-- Header : Start --}}
    {{ isSet($header) ? $header : '' }}
    {{-- Header : End --}}

    {{-- Content : Start --}}
    {{ isSet($content) ? $content : '' }}
    {{-- Content : End --}}

    {{-- Footer : Start --}}
    {{ isSet($footer) ? $footer : ''  }}
    {{-- Footer : End --}}


    {{ Asset::container('bootstrapper')->scripts() }}
    {{ Asset::container('nicedit')->scripts() }}
    {{ Asset::container('multifile')->scripts() }}
    {{ HTML::script('js/api.js') }}
  </body>
</html>
