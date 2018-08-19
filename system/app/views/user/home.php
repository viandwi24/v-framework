@extends('user.template')
@set('title', 'Home')


@content('isi')
        <a href="<?php echo base_url('user/insert');?>" class="btn btn-success btn-xs" style="margin-bottom: 15px;"> <i class="fa fa-plus"></i> Tambah</a>

        <table class="table table-sm table-hover table-bordered">
            <thead class="thead-dark">
              <tr>
                <th scope="col" class="text-center">#</th>
                  <th scope="col" class="text-center">Nama</th>
                    <th scope="col" class="text-center">Email</th>
                    <th scope="col" class="text-center">Kelas</th>
                    <th scope="col" class="text-center">...</th>
                </tr>
            </thead>
          <tbody>
            <?php 
            $no = 0;
            ##ambil data dari controller lalu lakukan looping untuk mengeluarkan data user menjadi tabel
            foreach ($data_user as $user) {
              $no++;
            ?>
            <tr>
              <th scope="row" class="text-center"><?php echo $no;?></th>
              <td scope="row"><?php echo $user->nama;?></td>
              <td scope="row"><?php echo $user->email;?></td>
              <td scope="row"><?php echo $user->kelas;?></td>
              <td class="text-center">
                <a href="<?php echo base_url('user/delete/' . $user->id);?>" class="btn btn-danger btn-xs"> <i class="fa fa-trash"></i> </a>
                <a href="<?php echo base_url('user/update/' . $user->id);?>" class="btn btn-warning btn-xs"> <i class="fa fa-edit"></i> </a>
              </td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
@endcontent