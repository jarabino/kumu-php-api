<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Cache;

class githubApiController extends Controller
{
    
    public function users($usernames) 
    {
        /* get names from uri */
        $usernames = explode(',', $usernames);
        $usernames = array_slice($usernames, 0, 10);
        /* Set the base URL  */
        $http = Http::baseUrl('https://api.github.com/users/');
        $cache = [];

        foreach($usernames as $username) {
            /* check and retrieve from cache */
            if (Cache::has($username)) {
                $cache[] = Cache::get($username);
                continue;
            }
            /* request users from github */
            $promises[] = $http->async()->get($username)->then( function ($response) {
                $profile = json_decode($response->getBody(), true);

                if (isset($profile['login'])) {
                    $user = [
                        'name' => $profile['name'],
                        'login' => $profile['login'],
                        'company' => $profile['company'],
                        'followers' => $profile['followers'],
                        'public_repos' => $profile['public_repos'],
                        'average_public_repo_followers' => $profile['followers'] / $profile['public_repos']
                    ];
                    /* cache then request usernames */
                    Cache::remember($profile['login'], 120, function () use ($user) {
                        return $user;
                    });
                    return $user;
                }

            });
        }
        $responses = [];
        /* check if theres a request made */
        if(!empty($promises)) {
            $responses = Utils::all($promises)->wait();
        }
        /* remove null value */
        $results = array_filter(array_merge($responses, $cache));

        /* prevent sorting if result is empty */
        if(!empty($results)) {
            array_multisort(array_column($results, 'name'), SORT_ASC, $results);
        }
        
        return response()->json($results);
    }
}
