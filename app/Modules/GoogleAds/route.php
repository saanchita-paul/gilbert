<?php

use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;

    const SCOPE = 'https://www.googleapis.com/auth/adwords';
 const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';
// const CLIENT_ID = "341267162907-33l9lv2h60pa5dibtg5cvt8des4qm7fd.apps.googleusercontent.com";#shihab
// const CLIENT_ID = "546429654589-0i3nh33ra3lko0k7t3hcvlqlnmqh5t8u.apps.googleusercontent.com"; #lenin
 const CLIENT_ID = "857887594990-b3t6f6vagprpst6d281aivlbjtegkb14.apps.googleusercontent.com"; #hood
// const CLIENT_SECRET = "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"; #shihab
// const CLIENT_SECRET = "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"; #lenin
 const CLIENT_SECRET = "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"; #hood
  const REDIRECT_URL = "https://enk2.leninsheikh.com/google-ads/callback";
//private const REDIRECT_URL = "http://localhost:8000/callback";
Route::get('authorize', function(){
    $oauth2 = new OAuth2(
        [
            'clientId' => CLIENT_ID,
            'clientSecret' => CLIENT_SECRET,
            'authorizationUri' => AUTHORIZATION_URI,
            'redirectUri' => REDIRECT_URL,
            'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
            'scope' => SCOPE,
            'state' => sha1(openssl_random_pseudo_bytes(1024))
        ]
    );
    return redirect(urldecode($oauth2->buildFullAuthorizationUri(['access_type' => 'offline'])));
});
Route::get('callback', function(\Illuminate\Http\Request $request) {
    $oauth2 = new OAuth2([
        'clientId' => CLIENT_ID,
        'clientSecret' => CLIENT_SECRET,
        'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
        'scope' => SCOPE,
        'redirectUri' => REDIRECT_URL,
        'state' => sha1(openssl_random_pseudo_bytes(1024))
    ]);
    $oauth2->setCode($request->get('code'));
    $authToken = $oauth2->fetchAuthToken();
    dump($authToken);
    dd($authToken);
    $refreshToken = $authToken['refresh_token'];
    $oauth2->setRefreshToken($refreshToken);
    info("GOOGLE TOKEN", [
        'token' => $refreshToken
    ]);
    if ($refreshToken) {
        dump("SUCCESS");
        dump($refreshToken);
        $oAuth2Credential = new UserRefreshCredentials(
            ['https://www.googleapis.com/auth/adwords'],
            [
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'refresh_token' => $refreshToken
            ]
        );
        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->fromFile('/home/lnn/www/src/gbert/google_ads_php.ini')
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        dd($request->toArray(), $oauth2, $oAuth2Credential, $googleAdsClient);
    }
});
