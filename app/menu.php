<?php

/**
 * Created by PhpStorm.
 * User: super1
 * Date: 18/11/2016
 * Time: 14:56
 */
class AppMenuEntry
{
    // settings menu; menulevel, accesslevel, id, fa icon,
//                                  isDisplayed, display, moduleName
    public $menuTmpl = array(
        array(0, 'all', 'home', 'fa fa-home fa-fw',
            1, 'Beranda'),

        array(0, 'all', 'sell', 'fa fa-chevron-circle-right fa-fw',
            1, 'Transaksi Penjualan'),

        array(0, 'admin', 'news', 'fa fa-chevron-circle-right fa-fw',
            1, 'Berita'),

        array(0, 'all', '', 'fa fa-reorder fa-fw',
            1, 'Data Master'),
        array(1, 'admin', 'units', 'fa',
            1, 'Unit'),
        array(1, 'unit', 'citizens', 'fa',
            1, 'Nasabah'),
        array(1, 'all', 'catproducts', 'fa',
            1, 'Kategori Produk'),
        array(1, 'all', 'subcatproducts', 'fa',
            1, 'Sub Kategori Produk'),
        array(1, 'all', 'myprice', 'fa',
            1, 'Harga Pokok'),

        array(0, 'all', '', 'fa fa-reorder fa-fw',
            1, 'Laporan'),
        array(1, 'all', 'repsubcat', 'fa',
            1, 'Sub Kategori Sampah'),
        array(1, 'all', 'repcitizen', 'fa',
            1, 'Nasabah'),
        array(1, 'all', 'repbooking', 'fa',
            1, 'Pembukuan'),

        array(0, 'admin', '', 'fa fa-reorder fa-fw',
            1, 'Laporan - Admin'),
        array(1, 'admin', 'admin.repadmin-citizens', 'fa',
            1, 'Seluruh Nasabah'),
        array(1, 'admin', 'admin.repadmin-unittrx', 'fa',
            1, 'Transaksi Unit'),
        array(1, 'admin', 'admin.repadmin-booking', 'fa',
            1, 'Pembukuan Unit'),

        //non visible
        array(0, 'admin,unit', 'usermgmt', 'fa', 0, 'usermgmt'),
    );

}