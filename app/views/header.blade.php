<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>WTRS</title>

    <!-- Bootstrap core CSS -->
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrapValidator.min.css') }}

    <!-- Metis Menu Plugin CSS -->
    {{ HTML::style('css/plugins/metisMenu/metisMenu.min.css') }}
    {{ HTML::style('css/plugins/dataTables.bootstrap.css') }}

    {{ HTML::style('css/sb-admin-2.css') }}
    {{ HTML::style('css/plugins/morris.css') }}
    {{ HTML::style('font-awesome-4.1.0/css/font-awesome.min.css') }}
    {{ HTML::style('css/datepicker.css') }}
    {{ HTML::style('css/WTRS.css') }}


    {{ HTML::script('js/jquery.min.js') }}
    {{ HTML::script('js/angular.min.js') }}
    {{ HTML::script('js/app.js') }}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
    {{ HTML::script('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}
    {{ HTML::script('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') }}
        <![endif]-->
  </head>

  <body ng-app="WTRS">
