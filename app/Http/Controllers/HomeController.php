<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $campaigns = [
            [
                'title' => 'Bantu Korban Banjir Medan',
                'description' => 'Mari bantu saudara kita yang terkena bencana banjir.',
                'category' => 'Bencana',
                'image' => 'https://picsum.photos/600/400?random=1',
                'raised' => 75000000,
                'target' => 100000000,
                'donors' => 210,
            ],
            [
                'title' => 'Pendidikan Anak',
                'description' => 'Bantu pendidikan anak-anak kurang mampu.',
                'category' => 'Pendidikan',
                'image' => 'https://picsum.photos/600/400?random=2',
                'raised' => 45000000,
                'target' => 80000000,
                'donors' => 156,
            ],
            [
                'title' => 'Bantu Biaya Pengobatan',
                'description' => 'Bantu biaya pengobatan pasien yang membutuhkan.',
                'category' => 'Kesehatan',
                'image' => 'https://picsum.photos/600/400?random=3',
                'raised' => 30000000,
                'target' => 50000000,
                'donors' => 98,
            ],
        ];

        return view('home', [
            'title' => 'Autopahala',
            'campaigns' => $campaigns,
        ]);
    }
}