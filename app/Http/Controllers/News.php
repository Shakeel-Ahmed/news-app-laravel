<?php

    namespace App\Http\Controllers;

    use Illuminate\Support\Facades\Http;

    class News extends Controller
    {
        protected Responder $responder;

        public function __construct()
        {
            $this->responder = new Responder;

            define('GRD_CALL', config('app.GRD_URL') . config('app.GRD_KEY'));
            define('NYT_CALL', config('app.NYT_URL') . config('app.NYT_KEY'));
        }

        public function Index($source)
        {
            $validate = Login::AuthToken();
            if($validate === false) return $this->responder->message([401,'Error: Unauthorized request', '']);

            $search = request('search');
            $page = request('page') ?? 1;
            $articles = [];

            switch ($source) {
                case ('grd'):
                    {
                        $newsData = json_decode( Http::get(GRD_CALL . '&q=' . $search . '&page=' . $page), true );
                        $articles = $this->ArticleMaker($newsData['response']['results'], 'grd');
                    }
                    break;
                case ('nyt'):
                    {
                        $newsData = json_decode( Http::get(NYT_CALL . '&q=' . $search . '&page=' . $page), true );
                        $articles = $this->ArticleMaker($newsData['response']['docs'], 'nyt');
                    }
                    break;
            }

            $data = [
                '200',
                'Success',
                $page,
                $page + 1,
                $page - 1,
                $articles
            ];

            return $this->responder->NewsResponse($data);
        }

        private function ArticleMaker(array $articles, string $source ): array
        {
            $index = 0;
            $data = [];
            if($source === 'nyt')
            {
                foreach ($articles as $article)
                {
                    $data[$index]['headline'] = $article['headline']['main'];
                    $data[$index]['paragraph'] = $article['lead_paragraph'];
                    $data[$index]['url'] = $article['web_url'];
                    $data[$index]['image'] = isset($article['multimedia'][0]['url']) ? 'https://www.nytimes.com/'.$article['multimedia'][0]['url'] : '';
                    $data[$index]['source'] = $article['source'];
                    $data[$index]['published'] = $article['pub_date'];
                    $index++;
                }
            }
           if($source === 'grd')
            {
                foreach ($articles as $article)
                {
                    $data[$index]['headline'] = $article['webTitle'];
                    $data[$index]['paragraph'] = '';
                    $data[$index]['url'] = $article['webUrl'];
                    $data[$index]['image'] = '//'.$_SERVER['HTTP_HOST'].'/images/grd.svg';
                    $data[$index]['source'] = 'The Guardian News';
                    $data[$index]['published'] = $article['webPublicationDate'];
                    $index++;
                }
            }
            return $data;
        }
    }
