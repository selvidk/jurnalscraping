<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataJurnalM;
use App\Models\DataPtM;
use App\Models\DataKategoriM;
use App\Models\IndexKategoriM;
use App\Models\DataPubM;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ScrapingC extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->jurnal   = new DataJurnalM();
        $this->pt       = new DataPtM();
        $this->kategori = new DataKategoriM();
        $this->iKategori= new IndexKategoriM();
        $this->pub      = new DataPubM();
    }

    public function index()
    {
        $data_db      = $this->jurnal->data()->count();
        $client       = new Client();
        $url_sinta    = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals/index');
        // $total_s1 = $s1->filter('table[class="uk-table"] > caption')->text();

        $data_sinta   = $url_sinta->filter('div[class="text-center pagination-text"]')->text();
        $sinta_fix    = 'Jumlah '.str_replace(["1 of", "Records"], ["", "Data"], $data_sinta);
        $max_page     = substr(explode("|", $sinta_fix)[0], 12);

        return view('admin.pengaturan', ['total' => $data_db,'coba' => $sinta_fix, 'max_page' => $max_page]);

        // $s1       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=1');
        // $total_s1 = $s1->filter('table[class="uk-table"] > caption')->text();

        // $s2       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=2');
        // $total_s2 = $s2->filter('table[class="uk-table"] > caption')->text();

        // $s3       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=3');
        // $total_s3 = $s3->filter('table[class="uk-table"] > caption')->text();

        // $s4       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=4');
        // $total_s4 = $s4->filter('table[class="uk-table"] > caption')->text();

        // $s5       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=5');
        // $total_s5 = $s5->filter('table[class="uk-table"] > caption')->text();

        // $s6       = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?q=&search=1&sinta=6');
        // $total_s6 = $s6->filter('table[class="uk-table"] > caption')->text();
        // return view('admin.pengaturan', ['total' => $total, 's1' => $total_s1, 's2' => $total_s2, 's3' => $total_s3, 's4' => $total_s4, 's5' => $total_s5, 's6' => $total_s6,]);
    }

    public function radioCheck(Request $request)
    {
        $query        = $request->peringkat;
        $response     = $this->jurnal->data()->where('peringkat', $query)->count();
        $page         = $response/10;

        if (is_float($page) == true) {
            $floor = floor($page);
            if($response >= 10 && $response < 20) {
                $page_check = $floor+1;
            }else{
                $page_check = $floor;
            }
        } else {
            $page_check = $page;
        }
        $page_check == 0 ? $page_check = 1 : $page_check=$page_check;
        return response()->json([
            'data_sinta'=> $page_check,
            'data_db'   => $response,
            'sinta'     => $query,
        ]);
    } 

    public function scrapJurnal(Request $request)
    {
        $request->validate([
            'start_page' => 'required',
            'end_page'   => 'required|numeric|min:'.$request->start_page
        ]);

        $end_page   = $request->end_page;
        $client     = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        // $client = new Client();
        for ($i=$request->start_page; $i<=$end_page; $i++) {
            $request     = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?page='.$i);
            $detail      = $request->filter('div[class="col-lg meta-side"]')->each(function ($node) {
                // try {
                        $id_jurnal   = explode('/',$node->filter('div[class="affil-name mb-3"] > a')->attr('href'))[5];
                    $nama_jurnal = $node->filter('div[class="affil-name mb-3"]')->text();
    
                    $list_url    = $node->filter('div[class="affil-abbrev"] > a')->each(function ($node2) {
                        return $node2->attr('href');
                    });
    
                    $url_jurnal  = $list_url[2];
                    $jadwal      = $this->scrapJadwal2($url_jurnal);
                    $url_pt      = $node->filter('div[class="affil-loc mt-2"] > a:nth-of-type(1)')->attr('href');
    
                    $a           = new Client();
                    
                    try {
                        $request_pt  = $a->request('GET', $url_pt);
                        $nama_pt     = $request_pt->filter('h3')->text();
                        $alamat_pt   = $request_pt->filter('div[class="meta-profile"] > a:nth-of-type(1)')->text();
                    } catch (\Throwable $th) {
                        $nama_pt     = $node->filter('div[class="affil-loc mt-2"] > a:nth-of-type(1)')->text();
                        $alamat_pt   = null;
                    }
                    
                    $peringkat   = substr($node->filter('div[class="stat-prev mt-2"] > span:nth-of-type(1)')->text(), 1, 1);
    
                    $cek_kategori= strpos($node->filter('div[class="profile-id"]')->text(), 'Subject Area :');
                    if (!$cek_kategori) {
                        $kategori = null;
                    }else {
                        $kategori = explode(':', $node->filter('div[class="profile-id"]')->text())[3];
                    }
    
                    $jurnal = [
                        'id_jurnal'   => $id_jurnal,
                        'nama_jurnal' => $nama_jurnal,
                        'pt'          => substr($nama_pt, 0, 75),
                        'alamat_pt'   => substr($alamat_pt, 0, 255),
                        'url'         => $url_jurnal,
                        'peringkat'   => $peringkat,
                        'kategori'    => $kategori,
                        'jadwal'      => $jadwal,
                    ];
                    $id_pt = $this->ptToDb($jurnal);
                    $this->jurnalToDb($id_pt, $jurnal);
                    $this->kategoriToDb($jurnal);
                    $this->jadwalToDb($jurnal);
    
                    return $jurnal;
                // } catch (\Throwable $th) {
                //     return $th->getMessage();
                // }
            });
                // $aa[] = $this->scrapJadwal2($detail);
            // for ($j=0; $j <count($detail) ; $j++) { 
            //     $cek_pt     = $this->pt->data()->where(['nama_pt' => $detail[$j]['pt']])->count();
            //     if ($cek_pt == 0){ 
            //         $this->pt->data()->insert(['nama_pt' => $detail[$j]['pt'], 'alamat' => $detail[$j]['alamat_pt']]);
            //     }
            //     $pt         = $this->pt->data()->where(['nama_pt'=> $detail[$j]['pt']])->first();
            //     $id_pt      = $pt->id_pt;

            //     $cek_jurnal = $this->jurnal->data()->where('id_jurnal', $detail[$j]['id_jurnal'])->count();

            //     if ($cek_jurnal == 0) {
            //         $this->jurnal->data()->insert([
            //             'id_jurnal'   => $detail[$j]['id_jurnal'],
            //             'nama_jurnal' => $detail[$j]['nama_jurnal'],
            //             'id_pt'       => $id_pt,
            //             'url'         => $detail[$j]['url'],
            //             'peringkat'   => $detail[$j]['peringkat'],
            //             'tgl_buat'    => date('Y-m-d H:i:s'),
            //         ]);
            //     } else {
            //         $this->jurnal->data()->where('id_jurnal', $detail[$j]['id_jurnal'])
            //              ->update([
            //                 'nama_jurnal' => $detail[$j]['nama_jurnal'],
            //                 'id_pt'       => $id_pt,
            //                 'url'         => $detail[$j]['url'],
            //                 'peringkat'   => $detail[$j]['peringkat'],
            //                 'tgl_ubah'    => date('Y-m-d H:i:s'),
            //             ]);
            //     }
                
            //     if ($detail[$j]['kategori'] != null) {
            //         $kategori      = explode(',', $detail[$j]['kategori']);

            //         for ($k=0; $k < count($kategori); $k++) { 
            //             $kategori_fix = str_replace(" ","",$kategori[$k]);
            //             $cek_i_kategori = $this->iKategori->data()->where('nama_asing', $kategori_fix)->count();
            //             if ($cek_i_kategori == 0) {
            //                 $this->iKategori->data()->insert(['nama_asing' => $kategori_fix]);
            //             }

            //             $i_kategori = $this->iKategori->data()->where('nama_asing', $kategori_fix)->first();
            //             $i_kategori->nama_indonesia == null ? $nama_kategori = $kategori_fix : $nama_kategori = $i_kategori->nama_indonesia;

            //             $detail_kategori = [
            //                 'id_jurnal'     => $detail[$j]['id_jurnal'],
            //                 'nama_kategori' => $nama_kategori,
            //             ];

            //             $cek_kategori    = $this->kategori->data()->where($detail_kategori)->get();

            //             count($cek_kategori) == 0 ? $this->kategori->data()->insert($detail_kategori) : $this->kategori->data()->where('id_kategori', $cek_kategori[0]->id_kategori)->update($detail_kategori);
            //         }
            //     }
            // }
        }
        // return $detail;
        return redirect()->route('pengaturan')->with('sukses', 'Berhasil menambahkan data.');
        

        // for ($i=$request->start_page; $i<=$request->end_page; $i++) { 
        //     // request detail jurnal di SINTA
        //     $request     = $client->request('GET', 'https://sinta.kemdikbud.go.id/journals?page='.$i.'&q=&search=1&sinta='.$request->peringkat.'&pub=&city=&pubid=&area=');
        //     $url_detail  = $request->filter('a[class="text-blue"]')->each(function ($node) {
        //         return 'https://sinta.kemdikbud.go.id'.$node->attr('href');
        //     });

        //     for ($j=0; $j<count($url_detail); $j++) { 
        //         $data       = $client->request('GET', $url_detail[$j]);

        //         $data_jurnal  = $data->filter('body')->each(function ($node) {
        //             $nama_jurnal = $node->filter('div[class="au-data uk-vertical-align-middle"] > div[class="au-name"]')->text();
            
        //             $pt          = $node->filter('div[class="au-data uk-vertical-align-middle"] > div[class="au-department"]')->text();

        //             $alamat_pt   = $node->filter('ul[class="uk-list uk-list-line"] > li')->eq(2)->text();
        //             // $id_pt       = DB::table('t_pt')->where('nama_pt', $pt)->get();
            
        //             $url         = $node->filter('ul[class="uk-list uk-list-line"] > li > a[href]')->attr('href');
    
        //             $sinta       = substr($node->filter('div[class="stat2-val"]')->text(), 1);
            
        //             $jurnal = [
        //                 'nama_jurnal' => $nama_jurnal,
        //                 'pt'          => substr($pt, 0, 75),
        //                 'alamat_pt'   => substr($alamat_pt, 0, 255),
        //                 'url'         => $url,
        //                 'peringkat'   => $sinta,
        //             ];
        //             return $jurnal;
        //         });

        //         $cek_pt  = $this->pt->data()->where(['nama_pt' => $data_jurnal[0]['pt']])->count();
        //         if ($cek_pt == 0){ 
        //             $this->pt->data()->insert(['nama_pt' => $data_jurnal[0]['pt']]);
        //         }

        //         $pt     = $this->pt->data()->where(['nama_pt'=> $data_jurnal[0]['pt']])->get();
        //         $id_pt  = $pt[0]->id_pt;

        //         $jurnal_fix = [
        //             'id_jurnal'   => explode("=", $url_detail[$j])[1],
        //             'id_pt'       => $id_pt, 
        //             'nama_jurnal' => substr($data_jurnal[0]['nama_jurnal'], 0, 100),
        //             'peringkat'   => $data_jurnal[0]['peringkat'],
        //             'url'         => $data_jurnal[0]['url'],
        //             'tgl_buat'    => date('Y-m-d H:i:s')
        //         ];

        //         $cek_jurnal   = $this->jurnal->data()->where('id_jurnal', $jurnal_fix['id_jurnal'])->count();

        //         if ($cek_jurnal == 0) {
        //             $this->jurnal->data()->insert($jurnal_fix);
        //         } 
        //         else {
        //             $this->jurnal->data()->where('id_jurnal', $jurnal_fix['id_jurnal'])->update($jurnal_fix);
        //         }
                
        //         // $cek_jurnal == 0 ? $this->jurnal->data()->insert($jurnal_fix) : $this->jurnal->data()->where('id_jurnal', $jurnal_fix['id_jurnal'])->update($jurnal_fix);
        //         // $this->jurnal->data()->insert($jurnal_fix);
                
        //         $kategori = $data->filter('a[class="area-item-small"]')->each(function ($node) {
        //             return $node->text();
        //         });

        //         for ($k=0; $k < count($kategori) ; $k++) { 
        //             $detail_kategori = [
        //                 'id_jurnal'     => $jurnal_fix['id_jurnal'],
        //                 'nama_kategori' => $kategori[$k],
        //             ];
        
        //             $cek_kategori = $this->kategori->data()->where($detail_kategori)->get();

        //             count($cek_kategori) == 0 ? $this->kategori->data()->insert($detail_kategori) : $this->kategori->data()->where('id_kategori', $cek_kategori[0]->id_kategori)->update($detail_kategori);
        //         }
        //     } 
        //     // return $jurnal_fix;
        // }
        // return redirect()->route('pengaturan')->with('sukses', 'Berhasil menambahkan data (Jurnal).');
    }
    
    public function ptToDb ($jurnal) {
        $cek_pt     = $this->pt->data()->where(['nama_pt' => $jurnal['pt']])->count();
        if ($cek_pt == 0){ 
            $this->pt->data()->insert(['nama_pt' => $jurnal['pt'], 'alamat' => $jurnal['alamat_pt']]);
        }
        $pt         = $this->pt->data()->where(['nama_pt'=> $jurnal['pt']])->first();
        $id_pt      = $pt->id_pt;
        return $id_pt;
    }
    
    public function jurnalToDb ($id_pt, $jurnal) {
        $cek_jurnal = $this->jurnal->data()->where('id_jurnal', $jurnal['id_jurnal'])->count();

        if ($cek_jurnal == 0) {
            $this->jurnal->data()->insert([
                'id_jurnal'   => $jurnal['id_jurnal'],
                'nama_jurnal' => $jurnal['nama_jurnal'],
                'id_pt'       => $id_pt,
                'url'         => $jurnal['url'],
                'peringkat'   => $jurnal['peringkat'],
                'tgl_buat'    => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->jurnal->data()->where('id_jurnal', $jurnal['id_jurnal'])
                 ->update([
                    'nama_jurnal' => $jurnal['nama_jurnal'],
                    'id_pt'       => $id_pt,
                    'url'         => $jurnal['url'],
                    'peringkat'   => $jurnal['peringkat'],
                    'tgl_ubah'    => date('Y-m-d H:i:s'),
                ]);
        }
    }
    
    public function kategoriToDb ($jurnal) {
        if ($jurnal['kategori'] != null) {
            $kategori      = explode(',', $jurnal['kategori']);

            for ($k=0; $k < count($kategori); $k++) { 
                $kategori_fix = str_replace(" ","",$kategori[$k]);
                $cek_i_kategori = $this->iKategori->data()->where('nama_asing', $kategori_fix)->count();
                if ($cek_i_kategori == 0) {
                    $this->iKategori->data()->insert(['nama_asing' => $kategori_fix]);
                }

                $i_kategori = $this->iKategori->data()->where('nama_asing', $kategori_fix)->first();
                $i_kategori->nama_indonesia == null ? $nama_kategori = $kategori_fix : $nama_kategori = $i_kategori->nama_indonesia;

                $detail_kategori = [
                    'id_jurnal'     => $jurnal['id_jurnal'],
                    'nama_kategori' => $nama_kategori,
                ];

                $cek_kategori    = $this->kategori->data()->where($detail_kategori)->get();

                count($cek_kategori) == 0 ? $this->kategori->data()->insert($detail_kategori) : $this->kategori->data()->where('id_kategori', $cek_kategori[0]->id_kategori)->update($detail_kategori);
            }
        }
    }
    
    public function scrapJadwal2($url_jurnal)
    {
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        try {
            $get_url  = $client->request('GET', $url_jurnal);
            $cek_home = $get_url->filter('#home > a[href]')->attr('href');
        } catch (\Throwable $th) {
            // return $th->getMessage();
            // The current node list is empty 
            if ($th->getMessage() == 'The current node list is empty.') {
                $cek_home = '#home tidak ditemukan';
            } else {
                $cek_home = '#';
            }
        }

        if ($cek_home == '#home tidak ditemukan') {
            $cek_url  = substr($url_jurnal, -6);
        
            if ($cek_url == '/index') {
                $get_home = $url_jurnal;
            } else {
                $cek_url2  = substr($url_jurnal, -1);
                if ($cek_url2 == '/') {
                    $get_home = $url_jurnal.'index';
                } else {
                    $get_home = $url_jurnal.'/index';
                }
            }
        } else {
            $get_home = $cek_home;
        }

        if (filter_var($get_home, FILTER_VALIDATE_URL)) {
            $arc        = $this->archives($get_home);
            $dupl_arc   = array_unique($arc);
            $arc_fix    = array_values($dupl_arc);
            if (count($arc_fix) == 0) {
                $jadwal_about  = $this->about($get_home);
                if (count($jadwal_about) == 0) {
                    $jadwal_fix = $this->pubFreq($get_home);
                } else {
                    $jadwal_fix = $jadwal_about;
                }
                // $jadwal_fix  = 'a';
            } 
            else {
                $jadwal_fix  = $arc_fix;
            }
        } else {
            $jadwal_fix = [];
        }
        return $jadwal_fix;
        // return redirect()->route('pengaturan')->with('sukses', 'Berhasil menambahkan data (Jadwal Publikasi).');
    }
    
    public function jadwalToDb($jurnal) 
    {
        if (count($jurnal['jadwal']) != 0) {
                sort($jurnal['jadwal']);
                for ($l=0; $l<count($jurnal['jadwal']); $l++) { 
                    $cek_jadwal     = $this->pub->data()->where([
                                        'id_jurnal' => $jurnal['id_jurnal'],
                                        'bulan'     => $jurnal['jadwal'][$l],
                                    ])->get();
                    
                    if (count($cek_jadwal) == 0) {
                        $this->pub->data()->insert([
                            'id_jurnal' => $jurnal['id_jurnal'],
                            'bulan'     => $jurnal['jadwal'][$l],
                        ]);
                    } else {
                        $this->pub->data()->where('id_publikasi_jurnal', $cek_jadwal[0]->id_publikasi_jurnal)->update([
                            'bulan'     => $jurnal['jadwal'][$l],
                        ]);
                    }
                }
            }
    }
    
    public function bulan ()
    {
        $bulan  = [
                    ['January','Januari'],
                    ['February', 'Februari'],
                    ['March','Maret'],
                    ['April', 'April'],
                    ['May', 'Mei'],
                    ['June','Juni'],
                    ['July', 'Juli'],
                    ['August', 'Agustus'],
                    ['September','September'],
                    ['October', 'Oktober'],
                    ['November','November'],
                    ['December', 'Desember']
                ];
        return $bulan;
    }

    public function archives($get_home)
    {
        $cc = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        //$cc = new Client(); 
        $request_home = $cc->request('GET', $get_home);
        try {
            $cek_arc = $request_home->filter('#archives > a[href]')->attr('href');
        } catch (\Throwable $th) {
            $cek_arc = '#archives tidak ditemukan';
        }

        if ($cek_arc == '#archives tidak ditemukan') {
            try {
                $url_normal = substr($get_home, 0,-5);
                $url_arc    = $url_normal.'issue/archive';
            } catch (\Throwable $th) {
                $url_arc    = null;
            }
        } else{
            $url_arc = $cek_arc;
        }
        try {
            $req_data_arc   = $cc->request('GET', $url_arc);
            // $a       = $req_data_arc->filter('#issues')->html();
            $list_arc       = $req_data_arc->filter('#issues')->each(function ($node_arc) {
                $data       = $node_arc->text();
                return $data;
            });
            $bulan          = $this->bulan();
            for($a=0; $a<count($list_arc); $a++){
                for($j=0; $j<count($bulan); $j++){
                    $arc_jadwal  = strripos($list_arc[$a], $bulan[$j][0]);
                    $arc_jadwal2 = strripos($list_arc[$a], $bulan[$j][1]);
                    if($arc_jadwal){
                        $arc_pub[] = $j+1;
                    } elseif($arc_jadwal2) {
                        $arc_pub[] = $j+1;
                    }
                }
            }
            if(!$arc_pub) {
                $arc_pub = [];
            }
        } catch (\Throwable $th) {
            // $arc_pub = $th->getMessage();
            if($th->getMessage() == 'The current node list is empty.') {
                $arc_pub = '#issues tidak ditemukan';
            } elseif($th->getMessage() == 'Undefined variable: arc_pub') {
                $arc_pub = '#issues tidak ditemukan';
            } else {
                $arc_pub = [];
            }
        }
        
        if($arc_pub == '#issues tidak ditemukan') {
            try {
                $req_data_arc2   = $cc->request('GET', $url_arc);
                // pkp_structure_main
                $list_arc2       = $req_data_arc2->filter('.issues_archive')->each(function ($node_arc) {
                    $data       = $node_arc->text();
                    return $data;
                });
                $bulan          = $this->bulan();
                if(count($list_arc2) != 0) {
                    for($a=0; $a<count($list_arc2); $a++){
                        for($j=0; $j<count($bulan); $j++){
                            $arc_jadwal  = strripos($list_arc2[$a], $bulan[$j][0]);
                            $arc_jadwal2 = strripos($list_arc2[$a], $bulan[$j][1]);
                            if($arc_jadwal){
                                $arc_pub2[] = $j+1;
                            } elseif($arc_jadwal2) {
                                $arc_pub2[] = $j+1;
                            }
                        }
                    }
                    if(!$arc_pub2) {
                        $arc_pub2 = [];
                    }
                } else {
                    $arc_pub2 = [];
                }
            } catch (\Throwable $th) {
                $arc_pub2 = [];
            }
        } else {
            $arc_pub2 = $arc_pub;
        }

        // try {
        //     $req_data_arc   = $cc->request('GET', $url_arc);
        //     // $list_arc       = $req_data_arc->filter('h4')->each(function ($node_arc) {
        //     //     $data       = $node_arc->text();
        //     //     return $data;
        //     // });
        //     $list_arc      = $req_data_arc->filter('.pkp_structure_main')->each(function ($node_arc) {
        //         $data       = $node_arc->text();
        //         return $data;
        //     });
        //     $bulan          = $this->bulan();
        //     for($a=0; $a<count($list_arc); $a++){
        //         for($j=0; $j<count($bulan); $j++){
        //             $arc_jadwal  = strripos($list_arc[$a], $bulan[$j][0]);
        //             $arc_jadwal2 = strripos($list_arc[$a], $bulan[$j][1]);
        //             if($arc_jadwal){
        //                 $arc_pub[] = $j+1;
        //             } elseif($arc_jadwal2) {
        //                 $arc_pub[] = $j+1;
        //             }
        //         }
        //     }
        //     // try {
        //     //     $list_arc       = $req_data_arc->filter('#issues')->each(function ($node_arc) {
        //     //         $data       = $node_arc->text();
        //     //         return $data;
        //     //     });
        //     //     $bulan          = $this->bulan();
        //     //     for($a=0; $a<count($list_arc); $a++){
        //     //         for($j=0; $j<count($bulan); $j++){
        //     //             $arc_jadwal  = strripos($list_arc[$a], $bulan[$j][0]);
        //     //             $arc_jadwal2 = strripos($list_arc[$a], $bulan[$j][1]);
        //     //             if($arc_jadwal){
        //     //                 $arc_pub[] = $j+1;
        //     //             } elseif($arc_jadwal2) {
        //     //                 $arc_pub[] = $j+1;
        //     //             }
        //     //         }
        //     //     }
        //     // } catch (\Throwable $th) {
        //     //     $list_arc      = $req_data_arc->filter('.pkp_structure_main')->each(function ($node_arc) {
        //     //         $data       = $node_arc->text();
        //     //         return $data;
        //     //     });
        //     //     $bulan          = $this->bulan();
        //     //     for($a=0; $a<count($list_arc); $a++){
        //     //         for($j=0; $j<count($bulan); $j++){
        //     //             $arc_jadwal  = strripos($list_arc[$a], $bulan[$j][0]);
        //     //             $arc_jadwal2 = strripos($list_arc[$a], $bulan[$j][1]);
        //     //             if($arc_jadwal){
        //     //                 $arc_pub[] = $j+1;
        //     //             } elseif($arc_jadwal2) {
        //     //                 $arc_pub[] = $j+1;
        //     //             }
        //     //         }
        //     //     }
        //     // }
            
        //     if(!$arc_pub) {
        //         $arc_pub = [];
        //     }
            
        // } catch (\Throwable $th2) {
        //     $arc_pub = [];
        // }
        return $arc_pub2;
    }

    public function pubFreq($get_home)
    {
        $cb = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        //$cb = new Client(); 
        $request_freq = $cb->request('GET', $get_home);
        try {
            $cek_freq = $request_freq ->filter('#about > a[href]')->attr('href');
        } catch (\Throwable $th) {
            $cek_freq = '#about tidak ditemukan';
        }

        if ($cek_freq == '#about tidak ditemukan') {
            try {
                $url_normal = substr($get_home, 0,-5);
                $url_freq  = $url_normal.'about';
            } catch (\Throwable $th) {
                $url_freq    = null;
            }
        } else{
            $url_freq = $cek_freq;
        }
        
        try {
            $req_data_freq = $cb->request('GET', $url_freq);
            $get_freq      = $cb->request('GET',$req_data_freq->filter('a[href$="publicationFrequency"]')->attr('href'));
            $freq_list     = $get_freq->filter('div[id=publicationFrequency]')->text();

            $bulan         = $this->bulan();
            for($k=0; $k<count($bulan); $k++){
                $freq_jadwal  = strripos($freq_list, $bulan[$k][0]);
                $freq_jadwal2 = strripos($freq_list, $bulan[$k][1]);
                if($freq_jadwal){
                    $freq_pub[] = $k+1;
                }elseif($freq_jadwal2){
                    $freq_pub[]= $k+1;
                }
            }
            if(!$freq_pub) {
                $freq_pub = [];
            }
        } catch (\Throwable $th) {
            $freq_pub = [];
        }
        return $freq_pub;
    }
    
    public function about($get_home)
    {
        $cb = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        //$cb = new Client(); 
        // $request_about = $cb->request('GET', $get_home);
        try {
            $cek_about = $request_home->filter('#about > a[href]')->attr('href');
        } catch (\Throwable $th) {
            $cek_about = '#about tidak ditemukan';
        }

        if ($cek_about == '#about tidak ditemukan') {
            try {
                $url_normal = substr($get_home, 0,-5);
                $url_about  = $url_normal.'about';
            } catch (\Throwable $th) {
                $url_about    = null;
            }
        } else{
            $url_about = $cek_about;
        }
        
        try {
            $req_data_about = $cb->request('GET', $url_about);
            $pub_list       = $req_data_about->filter('div[id=publicationFrequency]')->text();
            $bulan          = $this->bulan();
            for($k=0; $k<count($bulan); $k++){
                $pub_jadwal  = strripos($pub_list, $bulan[$k][0]);
                $pub_jadwal2 = strripos($pub_list, $bulan[$k][1]);
                if($pub_jadwal){
                    $about_pub[] = $k+1;
                }elseif($pub_jadwal2){
                    $about_pub[]= $k+1;
                } 
            }
            if(!$about_pub) {
                $about_pub = [];
            }
        } catch (\Throwable $th) {
            $about_pub = [];
        }
        return $about_pub;
    }
    
     public function radioCheckJurnal(Request $request)
    {
        $query      = $request->peringkat_pub;
        
                    
        if ($query == 0) {
            $cek_jurnal = $this->jurnal->data()
                    // ->where('t_jurnal.peringkat', $query)
                    ->count();
            $jurnal     = $this->jurnal->data()
                        // ->leftJoin('t_publikasi_jurnal', 't_jurnal.id_jurnal', 't_publikasi_jurnal.id_jurnal')
                        // ->where('t_jurnal.peringkat', $query)
                        // ->groupBy('t_jurnal.id_jurnal')
                        // ->whereNull('t_publikasi_jurnal.id_jurnal')
                        ->count();
        } else {
            $cek_jurnal = $this->jurnal->data()
                    ->where('t_jurnal.peringkat', $query)
                    ->count();
            $jurnal     = $this->jurnal->data()
                        // ->leftJoin('t_publikasi_jurnal', 't_jurnal.id_jurnal', 't_publikasi_jurnal.id_jurnal')
                        ->where('t_jurnal.peringkat', $query)
                        // ->groupBy('t_jurnal.id_jurnal')
                        // ->whereNull('t_publikasi_jurnal.id_jurnal')
                        ->count();
        }

        return response()->json([
            'cek'     => $cek_jurnal,
            'jurnal'  => $jurnal,
            'sinta'   => $query,
        ]);
    } 

    public function scrapJadwal(Request $request)
    {
        $client = new Client(HttpClient::create(['verify_peer' => false, 'verify_host' => false]));
        //$client = new Client();
        if ($request->peringkatPub == 0) {
            $jurnal = $this->jurnal->data()
                    ->orderBy('t_jurnal.id_jurnal', 'ASC')
                    ->select('t_jurnal.url', 't_jurnal.id_jurnal')
                    // ->where('t_jurnal.peringkat', $request->peringkatPub)
                    ->limit($request->end_pub)
                    ->offset($request->start_pub-1)
                    ->get();
        } else {
            $jurnal = $this->jurnal->data()
                    ->orderBy('t_jurnal.id_jurnal', 'ASC')
                    ->select('t_jurnal.url', 't_jurnal.id_jurnal')
                    ->where('t_jurnal.peringkat', $request->peringkatPub)
                    ->limit($request->end_pub)
                    ->offset($request->start_pub-1)
                    ->get();
        }
        // return $jurnal;
        for ($i=0; $i<count($jurnal); $i++) { 
            $aa = $jurnal[$i]->url;
            try {
                $get_url  = $client->request('GET', $jurnal[$i]->url);
                $cek_home = $get_url->filter('#home > a[href]')->attr('href');
            } catch (\Throwable $th) {
                // return $th->getMessage();
                // The current node list is empty 
                if ($th->getMessage() == 'The current node list is empty.') {
                    $cek_home = '#home tidak ditemukan';
                } else {
                    $cek_home = '#';
                }
            }
    
            if ($cek_home == '#home tidak ditemukan') {
                $cek_url  = substr($jurnal[$i]->url, -6);
            
                if ($cek_url == '/index') {
                    $get_home = $jurnal[$i]->url;
                } else {
                    $cek_url2  = substr($jurnal[$i]->url, -1);
                    if ($cek_url2 == '/') {
                        $get_home = $jurnal[$i]->url.'index';
                    } else {
                        $get_home = $jurnal[$i]->url.'/index';
                    }
                }
            } else {
                $get_home = $cek_home;
            }
    
            if (filter_var($get_home, FILTER_VALIDATE_URL)) {
                $arc        = $this->archives($get_home);
                $dupl_arc   = array_unique($arc);
                $arc_fix    = array_values($dupl_arc);
                if (count($arc_fix) == 0) {
                    $jadwal_about  = $this->about($get_home);
                    if (count($jadwal_about) == 0) {
                        $jadwal_fix  = $this->pubFreq($get_home);
                    } else {
                        $jadwal_fix = $jadwal_about;
                    }
                    // $jadwal_fix  = $jadwal_about;
                } 
                else {
                    $jadwal_fix  = $arc_fix;
                }

                if (count($jadwal_fix) != 0) {
                    sort($jadwal_fix);
                    for ($l=0; $l<count($jadwal_fix); $l++) { 
                        $cek_jadwal     = $this->pub->data()->where([
                                            'id_jurnal' => $jurnal[$i]->id_jurnal,
                                            'bulan'     => $jadwal_fix[$l],
                                        ])->get();
                        
                        if (count($cek_jadwal) == 0) {
                            $this->pub->data()->insert([
                                'id_jurnal' => $jurnal[$i]->id_jurnal,
                                'bulan'     => $jadwal_fix[$l],
                            ]);
                        } else {
                            $this->pub->data()->where('id_publikasi_jurnal', $cek_jadwal[0]->id_publikasi_jurnal)->update([
                                'bulan'     => $jadwal_fix[$l],
                            ]);
                        }
                    }
                }
            } 
            // } catch (\Throwable $th) {
            //     continue;
            // }
        }
        // return $jadwal_fix;
        return redirect()->route('pengaturan')->with('sukses', 'Berhasil menambahkan data (Jadwal Publikasi).');
            
        //     try {
        //         $url_arc  = $client->request('GET', $get_home->filter('#archives > a[href]')->attr('href'));
        //         $get_about= $client->request('GET', $get_home->filter('#about > a[href]')->attr('href'));
        //     } catch (\Throwable $th) {
        //         $url_normal = substr($get_home, 0,-5);
        //         $url_arc  = $client->request('GET', $url_normal.'issue/archive');
        //         $get_about= $client->request('GET', $url_normal.'about');
        //     }
        //     // $url_arc  = $client->request('GET', $get_home->filter('#archives > a[href]')->attr('href'));
        //     // return $url_arc;

           
        //     // return $get_about;
        //     // $url_pub  = $client->request('GET', $get_about);

        //     // try {
        //     //     $publikasi = $client->request('GET', $url_pub->filter('a[href$="publicationFrequency"]')->attr('href'))->filter('#publicationFrequency > p')->text();

        //     //     $dataPub[$i] = [
        //     //         'id_jurnal' => $jurnal[$i]->id_jurnal,
        //     //         'jadwal'    => $publikasi,
        //     //     ];
        //     // } catch (\Throwable $th) {
        //     //     $publikasi = null;
        //     //     $dataPub[$i] = [
        //     //         'id_jurnal' => $jurnal[$i]->id_jurnal,
        //     //         'jadwal'    => $publikasi,
        //     //     ];
        //     // }
            
        //     // try {
        //     //     $list_jadwal = $url_arc->filter('h4')->each(function ($node) {
        //     //         $data    = $node->text();

        //     //         $bulan   = [
        //     //             ['January','Januari'],
        //     //             ['February', 'Februari'],
        //     //             ['March','Maret'],
        //     //             ['April', 'April'],
        //     //             ['May', 'Mei'],
        //     //             ['June','Juni'],
        //     //             ['July', 'Juli'],
        //     //             ['August', 'Agustus'],
        //     //             ['September','September'],
        //     //             ['October', 'Oktober'],
        //     //             ['November','November'],
        //     //             ['December', 'Desember']
        //     //         ];

        //     //         for($i=0; $i<count($bulan); $i++){
        //     //             $jadwal = strripos($data, $bulan[$i][0]);
        //     //             if($jadwal){
        //     //                 $publikasi[] = $i+1;
        //     //             }
        //     //             else{
        //     //                 $jadwal = strripos($data, $bulan[$i][1]);
        //     //                 if($jadwal){
        //     //                     $publikasi[]= $i+1;
        //     //                 }
        //     //             }
        //     //         }
        //     //         return $publikasi;
        //     //     });
        //     //     $merge_pub  = call_user_func_array('array_merge', $list_jadwal);
        //     //     $data_fix   = array_unique($merge_pub);

        //     //     for ($j=0; $j <count($data_fix) ; $j++) { 
        //     //         $cek_jadwal = $this->pub->data()->where([
        //     //             'id_jurnal' => $jurnal[$i]->id_jurnal,
        //     //             'bulan'     => $data_fix[$j],
        //     //         ])->first();
                    
        //     //         if ($cek_jadwal == 0) {
        //     //             $this->pub->data()->insert([
        //     //                 'id_jurnal' => $jurnal[$i]->id_jurnal,
        //     //                 'bulan'     => $data_fix[$j],
        //     //             ]);
        //     //         } else {
        //     //             $this->pub->data()->where('id_jadwal', $cek_jadwal->id_jadwal)->update([
        //     //                 'id_jurnal' => $jurnal[$i]->id_jurnal,
        //     //                 'bulan'     => $data_fix[$j],
        //     //             ]);
        //     //         }
        //     //     }
        //     // } catch (\Throwable $th) {
        //     //     try {
        //             $list_jadwal = $client->request('GET', $get_about->filter('a[href$="publicationFrequency"]')->attr('href'))->filter('#publicationFrequency > p')->text();

                
        //         // var_dump($list_jadwal);
        //         // $list_jadwal = $get_about;
        //         // return $list_jadwal;

        //         $bulan   = [
        //             ['January','Januari'],
        //             ['February', 'Februari'],
        //             ['March','Maret'],
        //             ['April', 'April'],
        //             ['May', 'Mei'],
        //             ['June','Juni'],
        //             ['July', 'Juli'],
        //             ['August', 'Agustus'],
        //             ['September','September'],
        //             ['October', 'Oktober'],
        //             ['November','November'],
        //             ['December', 'Desember']
        //         ];
        //         for($j=0; $j<count($bulan); $j++){
        //             $jadwal = strripos($list_jadwal, $bulan[$j][0]);
        //             if($jadwal){
        //                 $publikasi[] = $j+1;
        //             }
        //             else{
        //                 $jadwal = strripos($list_jadwal, $bulan[$j][1]);
        //                 if($jadwal){
        //                     $publikasi[]= $j+1;
        //                 }
        //             }
        //         }

        //         for ($k=0; $k <count($publikasi) ; $k++) { 
        //             $cek_jadwal = $this->pub->data()->where([
        //                 'id_jurnal' => $jurnal[$i]->id_jurnal,
        //                 'bulan'     => $publikasi[$k],
        //             ])->first();
                    
        //             if ($cek_jadwal == 0) {
        //                 $this->pub->data()->insert([
        //                     'id_jurnal' => $jurnal[$i]->id_jurnal,
        //                     'bulan'     => $publikasi[$k],
        //                 ]);
        //             } else {
        //                 $this->pub->data()->where('id_jadwal', $cek_jadwal->id_jadwal)->update([
        //                     'id_jurnal' => $jurnal[$i]->id_jurnal,
        //                     'bulan'     => $publikasi[$k],
        //                 ]);
        //             }
        //         }
        //     }
        // // }
        // return redirect()->route('pengaturan')->with('sukses', 'Berhasil menambahkan data (Jadwal Publikasi).');
    }
}
