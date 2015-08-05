<?php
use Psr7Middlewares\Middleware;

class LanguageNegotiatorTest extends Base
{
    public function languagesProvider()
    {
        return [
            [
                'gl-es, es;q=0.8, en;q=0.7',
                [],
                'gl',
            ],[
                'gl-es, es;q=0.8, en;q=0.7',
                ['es', 'en'],
                'es'
            ],[
                'gl-es, es;q=0.8, en;q=0.7',
                ['en', 'es'],
                'es'
            ],[
                '',
                [],
                null
            ],[
                '',
                ['es', 'en'],
                'es'
            ],[
                '',
                ['en', 'es'],
                'en'
            ]
        ];
    }

    /**
     * @dataProvider languagesProvider
     */
    public function testLanguages($acceptLanguage, array $languages, $language)
    {
        $response = $this->execute(
            [
                Middleware::LanguageNegotiator($languages),
                function ($request, $response, $next) use ($language) {
                    $response->getBody()->write($request->getAttribute('LANGUAGE'));

                    return $response;
                },
            ],
            '',
            ['Accept-Language' => $acceptLanguage]
        );

        $this->assertEquals($language, (string) $response->getBody());
    }
}
