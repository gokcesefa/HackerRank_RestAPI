<?php 

class Result{
    
    protected static function getData($request){

        $url = curl_init();

        curl_setopt($url, CURLOPT_URL, $request);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        
        $data = curl_exec($url);

        curl_close($url);

        $jsonArrayResponse = json_decode($data,true);
        return $jsonArrayResponse;

    }

    // Return the total number of drawn matches in an exact year. 
    public function getNumDraws($year){

        $totalDrawnCount=0;
        $page = 1;
        $totalPages = $page;
        
        while( $page <= $totalPages ){

            $sendURL = "https://jsonmock.hackerrank.com/api/football_matches?year={$year}&page={$page}";
            $result =  Result::getData($sendURL);
            $totalPages = $result['total_pages'];

            foreach( $result['data'] as $value ){

                if( $value['team1goals'] == $value['team2goals'] ){
                    $totalDrawnCount++;
                }

            }
            $page++;
        }

        return $totalDrawnCount;
    }

    /* 
    * Total goals by a Team
    * The function is expected to return an Integer
    * The function accepts following parameters:
    * 1. String Team
    * 2. Integer Year
    * return integer the total number of goals sctored by the given team in all matches in the given year that the team played in
    * sample input (barcelona, 2011) -> output : 35
    */
    public static function getTotalGoals(string $team, int $year){
        
        $totalHome = 0;
        $totalVisitor=0;
        $page = 1;
        $homeTeam = "https://jsonmock.hackerrank.com/api/football_matches?year={$year}&team1={$team}&page={$page}";
        $visitorTeam = "https://jsonmock.hackerrank.com/api/football_matches?year={$year}&team2={$team}&page={$page}";

        $totalHomeTeam = Result::getData($homeTeam);
        $totalVisitorTeam = Result::getData($visitorTeam);
        
        while( $page <= $totalHomeTeam['total_pages'] ){

            foreach( $totalHomeTeam['data'] as $value ){

                $totalHome += (int)$value['team1goals'];

            }
            $page++;
        }

        $page = 1;
        while( $page <= $totalVisitorTeam['total_pages'] ){

            foreach($totalVisitorTeam['data'] as $value){

                $totalVisitor += (int)$value['team2goals'];

            }

            $page++;
        }

        $finalTotal = $totalHome + $totalVisitor;
        return $finalTotal;
    }
    
}