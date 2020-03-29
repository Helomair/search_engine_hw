<?php 
    require_once __DIR__ . '/paginate.class.php';
    require_once __DIR__ . '/gaisdbClient.class.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
  <div class="container">
        <form class="form-horizontal" action="./index.php" method="GET">
            <div class="form-group">
                <h1><label for="SearchTextInputLabel">Search it !</label></h1>
                <input type="text" class="form-control" id="SearchTextInput" name="SearchText">
                <small id="SearchHelp" class="form-text text-muted">Input what you want to search</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

        <div style="margin-top:30px">
            <div class="list-group">
            <?php
                if ($_GET["SearchText"] != null) {
                    $client = new GaisdbClient("http://nudb1.ddns.net:5804/nudb/query", "search_engine");

                    // Search info.
                    $query    = urlencode( ( isset( $_GET["SearchText"]) ) ? $_GET["SearchText"] : "" );
                    $page     = ( isset( $_GET["page"]) ) ? $_GET["page"] : 1;

                    // Search.
                    $result   = $client->get_recs($query, $page);
                    $segments = $client->get_segments($query, 1);

                    // Parse result.
                    $total = $result->result->cnt;
                    foreach ($result->result->recs as $rs) {
                        $rec = $rs->rec;
                        $rec->title = $client->highlight_segments($rec->title);
                        $rec->body = $client->highlight_segments($rec->body);
                        echo '
                        <a href="' .$rec->url . '" target="_blank" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">'. $rec->title .'</h5>
                            </div>
                            <small> ' . $rec->body . '</small>
                        </a> 
                        ';
                    }
                }
            ?>
            </div>
        </div>
        <nav style="margin-top:30px">
        <?php
                $paginator = new Paginator(10, $total, $page);  
                echo $paginator->createLinks(7, 'pagination justify-content-center',  $query); 
        ?>
        </nav>
    </div>
</body>
</html>