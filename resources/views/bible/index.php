<!DOCTYPE html>
<html xmlns:ng="http://angularjs.org" ng-app="BibleUI">
  <head>
    <meta charset="UTF-8" />
    <title>Đọc Thánh Kinh online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"/>

    <!-- AngularJS framework -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.10/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.10/angular-resource.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.10/angular-sanitize.min.js"></script>
    <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>

    <?php if (!empty($data['response']['isAdmin'])): ?>
        <link href="//cdn.rawgit.com/vitalets/angular-xeditable/0.1.8/dist/css/xeditable.css" rel="stylesheet">
        <script src="//cdn.rawgit.com/vitalets/angular-xeditable/0.1.8/dist/js/xeditable.min.js"></script>
        <script>
            var isAdmin = true;
        </script>
    <?php endif; ?>

    <!-- Custom code -->
    <script src="/assets/js/directives.js"></script>
    <script src="/assets/js/services.js"></script>
    <script src="/assets/js/search.js"></script>
    <script src="/assets/js/app.js"></script>
    <link rel="stylesheet" href="/assets/css/app.css"/>
  </head>
  <body ng-controller="BibleUIController">
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <bible-nav></bible-nav>
      </div>
    </div>

    <div class="container bible-ui">
      <div>
        <bible-ui></bible-ui>
      </div>
    </div>

    <div class="footer">
      <div class="container">
        <p class="text-muted">
          <a href="http://thanhkinhvietngu.net/">ThanhKinhVietNgu.net</a>
          | <a href="http://ngoiloi.thanhkinhvietngu.net">Bản dịch Ngôi Lời</a>
          | <a href="http://timhieuthanhkinh.net">TimHieuThanhKinh.net</a>
        </p>

        <p class="text-muted">
          <em>
            developed by <a href="mailto:thehongtt@gmail.com">Trương Thế Hồng</a>
          </em>
        </p>
      </div>
    </div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42270368-1', 'auto');
  ga('send', 'pageview');

</script>

  </body>
</html>
