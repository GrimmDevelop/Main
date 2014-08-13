<?php

use Grimm\Models\Location;

class LocationTableSeeder extends Seeder {

    public function run() {

        Location::create(array(
            'id' => 2950159,
            'name' => 'Berlin',
            'asciiname' => 'Berlin',
            'alternatenames' => 'BER,Beirlin,Beirlín,Berleno,Berlien,Berliin,Berliini,Berlijn,Berlim,Berlin,Berline,Berlini,Berlino,Berlyn,Berlynas,Berlëno,Berlín,Berlîn,Berlīne,Berolino,Berolinum,Birlinu,Bèrlîn,Estat de Berlin,Estat de Berlín,bai lin,barlina,beleullin,berlini,berurin,bexrlin,brlyn,perlin,Βερολίνο,Берлин,Берлін,Бэрлін,Բերլին,בערלין,ברלין,برلين,برلین,بېرلىن,ܒܪܠܝܢ,बर्लिन,বার্লিন,பெர்லின்,เบอร์ลิน,ბერლინი,ベルリン,柏林,베를린',
            'latitude' => 52.52437,
            'longitude' => 13.41053,
            'feature_class' => 'P',
            'feature_code' => 'PPLC',
            'country_code' => 'DE',
            'cc2' => '',
            'admin1_code' => '16',
            'admin2_code' => '00',
            'admin3_code' => '11000',
            'admin4_code' => '11000000',
            'population' => '3426354',
            'elevation' => '74',
            'dem' => '43',
            'timezone' => 'Europe/Berlin',
            'modification_date' => '2012-09-19'
        ));

    }

}
