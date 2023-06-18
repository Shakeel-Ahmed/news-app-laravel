<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;

    class Responder extends Controller
    {
        public function message( array $res_data ): array
        {
            return $this->Refactor(['code','message','data'], $res_data);
        }
        public function NewsResponse( array $newsData ): array
        {
            return $this->Refactor(['code','message','current','nextpage','backpage','articles'], $newsData);
        }
        private function Refactor( array $fields, array $data): array
        {
            $refactoring = [];
            foreach ($fields as $key => $field)
            {
                $refactoring[$field] = $data[$key];
            }
            return $refactoring;
        }
    }
