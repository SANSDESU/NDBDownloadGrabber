<?php
ini_set('max_execution_time', '3600'); //300 seconds = 5 minutes
set_time_limit(3600);
?>

<!DOCTYPE html>
<html lang="en" style="background: darkslategrey; color:darkcyan;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NDB Download Grabber</title>
    <link rel="shortcut icon" href="https://i.imgur.com/BuaoKdn.png" type="image/x-icon">
    <script src="src/jquery-3.7.1.min.js"></script>
    <style>
.card{border:7px solid #008b8b;border-radius:5px;padding:15px;margin:10px;width:350px;display:inline-block;vertical-align:top}.card img{max-width:100%;width:150px;border-radius:15px}.boxdownload{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:15px}.liner{border:3px solid #008b8b;padding:10px}.name{font-weight:700;text-align:center;color:#fffaf0}.listlink{margin-top:10px}.listlink a{display:block;margin-bottom:5px;color:#fffaf0;background:#008b8b;border-radius:10px;text-align:center;padding:7px 0;text-decoration:none}summary{font-weight:700;margin:0 20px;color:#fffaf0;background:#008b8b;border-radius:10px;text-align:left;padding:7px 0;padding-left:10px;cursor:pointer}details{margin-top:10px;margin-bottom:10px;font-size:large;color:dimgrey}details .stream{background:#f08080;width:60%}details .download{background:#6495ed;width:60%}.uncen .boxdownload .liner .name{margin-top:10px;margin-bottom:10px;font-size:large;color:#f08080}.show-stream>.list a{display:block;float:left;margin:5px 10px 10px 0;color:#fffaf0;background:#5f9ea0;padding:6.5px 10px;border-radius:5px;text-decoration:none}.show-stream .text{display:none}.vids{height:200px}.cr{position:absolute;left:50%;bottom:0;transform:translate(-50%,-50%)}
    </style>
    <script>
class streamS{constructor(a){this.id=a}setup(){var a=t;function t(a,e){var r=n();return(t=function(a,t){return r[a-=421]})(a,e)}function n(){var a=["3291240xZiBMz","each","click","div#list-","624330vmMjdB","log","1013454hQaIsY","filter","4373BDskNS","show","hide",'[href="',"381479xUPhVm","48bKHFZq","73539rTJGnB","addClass","preventDefault","449320rRBTao","not","active","added "];return(n=function(){return a})()}(function(a,n){for(var e=t,r=a();;)try{if(-parseInt(e(440))/1*(parseInt(e(424))/2)+-parseInt(e(425))/3+parseInt(e(428))/4+-parseInt(e(436))/5+-parseInt(e(438))/6+parseInt(e(423))/7+parseInt(e(432))/8==154992)break;r.push(r.shift())}catch(x){r.push(r.shift())}})(n,154992),$(a(435)+this.id)[a(433)](function(){var t,n,e=a,r=$(this).find("a");(t=$(r[e(439)](e(422)+location.hash+'"]')[0]||r[0]))[e(426)](e(430)),n=$(t[0].hash),r[e(429)](t)[e(433)](function(){$(this.hash)[e(421)]()}),$(this).on(e(434),"a",function(a){var r=e;t.removeClass(r(430)),n[r(421)](),t=$(this),n=$(this.hash),t[r(426)]("active"),n[r(441)](),a[r(427)]()})}),console[a(437)](a(431)+a(435)+this.id)}}let clearIntervalId=null;function clearConsole(){console.clear()}function startClearingInterval(){clearIntervalId=setInterval(clearConsole,100)}function stopClearingInterval(){clearInterval(clearIntervalId)}function clear(a){a?(startClearingInterval(),console.log("Console clearing started.")):(stopClearingInterval(),console.log("Console clearing stopped."))}clear(!0);
    </script>
</head>

<body>
    <span class="cr">Copyright © SANSDESU 2024</span>
    <center>
        <h1>NDB Download Grabber</h1>
        <p>If when getting the Download Url ERROR occurs, please use VPN and then send again.<br>And make sure your
            internet connection is fast.</p>

        <form enctype="multipart/form-data" action='' method="post">
            <input type="file" name="file" accept=".db" required>
            <input type="submit" value="Submit">
        </form>
        <h3 id="proccess">Current Proccess &#8594; <span style="color:slategray;"> [EMPTY] </span></h3>

        <div style="height:70vh; overflow-y: scroll;">

            <?php
            require 'vendor/autoload.php';
            use GuzzleHttp\Client;
            use GuzzleHttp\HandlerStack;
            use GuzzleHttp\Exception\RequestException;
            use GuzzleHttp\Middleware;

            if ($_FILES && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $allowedExtensions = array('db');
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Get the file extension

                if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                    echo "Error uploading file.";
                    exit;
                }

                $db = new SQLite3($_FILES['file']['tmp_name']);

                // Fetch data from the database
                $query = "SELECT id, post_type, post_id, type, title, image, time FROM bookmark ORDER BY title ASC";
                $result = $db->query($query);

                $final_result = '';

                // Display data in cards
                // Process each item one by one
                while ($row = $result->fetchArray()) {
                    echo '<script>document.getElementById(`proccess`).innerHTML = `Current Proccess &#8594; <span style="color:slategray;"> ' . $row['title'] . ' </span>`</script>';

                    ob_flush();
                    flush();

                    // Process the item
                    $itemResult = proccessData($row);

                    // Append item result to final result
                    $final_result .= $itemResult;

                    // Output the final result after processing each item
                    echo $final_result;

                    // Flush the output buffer to immediately send data to the client
                    ob_flush();
                    flush();

                    // Clear $final_result for the next iteration
                    $final_result = '';
                }

                echo '<script>document.getElementById(`proccess`).innerHTML = `Current Proccess &#8594; <span style="color:slategray;"> All Proccess Finished! </span>`</script>';

                ob_flush();
                flush();
                echo $final_result;

                // Close database connection
                $db->close();
            } elseif ($_FILES && $_FILES['file']['error'] != UPLOAD_ERR_OK) {
                echo "Error uploading file.";
                exit;
            }

            function proccessData($row)
            {
                $BASEDOMAIN = 'nekopoi.care';
                // Custom headers for bypass cloudflare
                $headers = array(
                    'Host' => $BASEDOMAIN,
                    'Sec-Ch-Ua' => '"Not(A:Brand";v="24", "Chromium";v="122"',
                    'Sec-Ch-Ua-Mobile' => '?0',
                    'Sec-Ch-Ua-Platform' => '"Windows"',
                    'Upgrade-Insecure-Requests' => '1',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.6261.112 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                    'Sec-Fetch-Site' => 'cross-site',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-User' => '?1',
                    'Sec-Fetch-Dest' => 'document',
                    'Referer' => $_SERVER['SERVER_PROTOCOL'] . "://" . $_SERVER['HTTP_HOST'],
                    'Accept-Encoding' => 'identity',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Priority' => 'u=0, i',
                    'Connection' => 'close'
                );

                $final_result = '';

                if ($row['title'] != null) {

                    $final_result .= '<div class="card">';

                    if ($row['image']) {
                        $final_result .= '<img src="' . $row['image'] . '" alt="Image">';
                    }
                    $final_result .= '<h2 style="font-size: 1.3em !important;"><a target="_blank" style="text-decoration:none; color:floralwhite;" href="https://' . $BASEDOMAIN . '/search/' . str_replace(' ', '+', $row['title']) . '">' . $row['title'] . '</a></h2>';

                    $initial_url = "https://$BASEDOMAIN/search/" . rawurlencode($row['title']);
                    // Create a Guzzle client with custom headers
                    $client = new Client([
                        'headers' => $headers,
                        'curl' => [
                            CURLOPT_FRESH_CONNECT => true,
                            CURLOPT_FORBID_REUSE => true,
                        ],
                    ]);

                    try {
                        try {
                            $initial_html = $client->get($initial_url)->getBody()->getContents();
                        } catch (Exception) {
                            try {
                                $initial_html = $client->get($initial_url)->getBody()->getContents();
                            } catch (Exception $e) {
                                $final_result .= "<span style='color: lightcoral;'>Error Connecting to Server 2 times, Please check your connection or use VPN</span>\n";
                            }
                        }

                        $mov_url = false;

                        if (preg_match('/<a href="([^"]+)"[^>]*>' . $row['title'] . '<\/a>/', $initial_html, $matches)) {
                            $movie_url = $matches[1];
                            $mov_url = true;

                        } else {

                            $movie_url = strtolower(str_replace(" ", "-", str_replace('XXX', '-', preg_replace("#[[:punct:]]#", "", str_replace('-', 'XXX', $row['title'])))));
                            $movie_url = "https://$BASEDOMAIN/hentai/$movie_url";
                            $mov_url = true;
                        }

                        if ($mov_url) {

                            try {
                                $movie_html = $client->get($movie_url)->getBody()->getContents();
                            } catch (Exception $e) {
                                try {
                                    $movie_html = $client->get($movie_url)->getBody()->getContents();
                                } catch (Exception $e) {
                                    $final_result .= "<span style='color: lightcoral;'>Error Getting Page from Server [$movie_url], Please check your connection or use VPN</span>";
                                }
                            }
                            // echo '<script>console.log("'.$movie_html.'")</script>';
                            $doc = new DOMDocument();
                            @$doc->loadHTML($movie_html);

                            // Create a DOMXPath object
                            $xpath = new DOMXPath($doc);

                            // XPath query to select elements with class "episodelist"
                            $query = '//div[contains(@class, "episodelist")]';

                            // Execute the query
                            $elements = $xpath->query($query);

                            // Check if elements are found
                            if ($elements->length > 0) {
                                // Loop through the found elements
                                foreach ($elements as $episodelist_div) {
                                    // Output the content of each element
                                    // echo $doc->saveHTML($episodelist_div);

                                    // Get all <a> elements within the episodelist_div
                                    $episodes = $episodelist_div->getElementsByTagName('a');
                                    foreach ($episodes as $episode) {
                                        // Get the URL of the episode
                                        $episode_url = $episode->getAttribute('href');
                                        $batch = 'Episode';
                                        $eps = 'EPS';

                                        // Perform the regular expression match
                                        if (preg_match('/episode-(\d+)/i', $episode_url, $episode_url_matches)) {
                                            $counte_eps = $episode_url_matches[1];
                                        } else {
                                            if (preg_match('/batch/i', $episode_url, $episode_url_matches)) {
                                                preg_match('/indonesia-(\d+)/i', $episode_url, $batch_matches);
                                                $counte_eps = $batch_matches[1];
                                                $batch = '[BATCH]';
                                                $eps = '[BATCH]';
                                            } else {
                                                $counte_eps = "?";
                                            }
                                        }
                                        // Output the episode link
                                        if (str_contains($episode_url, 'uncensored')) {
                                            $final_result .= "<details class='uncen'><summary>[UNCENSORED] $batch $counte_eps</summary><div style='height:10px;'></div><a target='_blank' style='text-decoration:none; color: dodgerblue;' href='$episode_url'>" . $row['title'] . " $batch $counte_eps [UNCENSORED]</a>";
                                        } else {
                                            $final_result .= "<details><summary>$batch $counte_eps</summary><div style='height:10px;'></div><a target='_blank' style='text-decoration:none; color: dodgerblue;' href='$episode_url'>" . $row['title'] . " $batch $counte_eps</a>";
                                        }

                                        try {
                                            $dwnld_url = $client->get($episode_url)->getBody()->getContents();
                                        } catch (Exception $e) {
                                            $final_result .= "<span style='color: lightcoral;'>Error Getting Episode from Server [$episode_url], Please check your connection or use VPN</span></details></details>";
                                            continue;
                                        }

                                        $doc_ = new DOMDocument();
                                        @$doc_->loadHTML($dwnld_url);

                                        // Create a DOMXPath object
                                        $xpath_ = new DOMXPath($doc_);

                                        $query_ = '//div[contains(@class, "boxdownload")]';

                                        // Execute the query
                                        $elements_ = $xpath_->query($query_);

                                        $streams_ = $doc_->getElementById('show-stream');
                                        $streams_html = $doc_->saveHTML($streams_);

                                        $ids = str_replace("https://$BASEDOMAIN/", '', $episode_url);
                                        $ids = str_replace('/', '', $ids);

                                        $links_ids = str_replace('href="#stream', 'href="#stream-' . $ids . '-', $streams_html);
                                        $links_ids = str_replace('id="stream', 'id="stream-' . $ids . '-', $links_ids);
                                        $links_ids = str_replace('id="list"', "id='list-$ids'", $links_ids);

                                        if (str_contains($episode_url, 'uncensored')) {
                                            $final_result .= "<details class='uncen'><summary class='stream'>[STREAM] $eps $counte_eps</summary><div style='height:10px;'></div>" . $links_ids . "</details><script>new streamS(`$ids`).setup();</script>";
                                            $final_result .= "<details class='uncen'><summary class='download'>[DOWNLOAD] $eps $counte_eps</summary><div style='height:10px;'></div><div class='boxdownload'>";
                                        } else {
                                            $final_result .= "<details><summary class='stream'>[STREAM] $eps $counte_eps</summary><div style='height:10px;'></div>" . $links_ids . "</details><script>new streamS(`$ids`).setup();</script>";
                                            $final_result .= "<details><summary class='download'>[DOWNLOAD] $eps $counte_eps</summary><div style='height:10px;'></div><div class='boxdownload'>";
                                        }



                                        // Check if elements are found
                                        if ($elements_->length > 0) {
                                            // Loop through the found elements
                                            foreach ($elements_ as $dwnld_div) {
                                                // Output the content of each element
                                                $dwnld_html = $doc_->saveHTML($dwnld_div);
                                                $doc__ = new DOMDocument();
                                                @$doc__->loadHTML($dwnld_html);

                                                // Create a DOMXPath object
                                                $xpath__ = new DOMXPath($doc__);

                                                $query__ = '//div[contains(@class, "liner")]';

                                                // Execute the query
                                                $elements__ = $xpath__->query($query__);

                                                // Check if elements are found
                                                if ($elements__->length > 0) {
                                                    // Loop through the found elements
                                                    foreach ($elements__ as $res_div) {
                                                        // Output the content of each element
                                                        $html = $doc__->saveHTML($res_div);
                                                        $uncen = false;

                                                        if (str_contains($html, 'UNCENSORED')) {
                                                            $uncen = true;
                                                        }

                                                        preg_match_all('/\[([0-9]+p)\]/', $html, $matches_);

                                                        foreach ($matches_[0] as $matched_string) {
                                                            $res = $uncen ? '[UNCENSORED] ' . $matched_string : $matched_string;
                                                            $html = preg_replace('/<div class="name">\K.*?(?=<\/div>)/', $res, $html);
                                                        }
                                                        $html = preg_replace('/<b>LINK<\/b>/', '', $html);

                                                        $final_result .= $html;
                                                    }
                                                }

                                            }
                                        }
                                        $final_result .= '</details></details>';
                                    }
                                }

                            } else {
                                $final_result .= "Episodelist not found\n";
                            }

                        } else {
                            $final_result .= "Episodelist not found\n";
                        }


                    } catch (Exception $e) {
                        $final_result .= "<span style='color: lightcoral;'>Error Connecting to Server [$initial_url], Please check your connection or use VPN</span></details></details>\n";
                    }

                    $final_result .= '</div>';
                }
                return $final_result;
            }



            ?>
        </div>
</body>

</html>