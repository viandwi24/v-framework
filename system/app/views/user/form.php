@extends('user.template')
@set('title', '<?php echo $card_title;?>')

@content('isi')
      <div class="card">
        <div class="card-header bg-dark text-white">
          <a href="<?php if ($card_title == 'Tambah Data'){echo '../user/';}else{echo '../';}?>" class="btn btn-xs btn-primary"><i class="fa fa-chevron-left"></i></a>
          Form <?php echo $card_title;?>
        </div>
        <div class="card-body">
          
          <form method="POST" action="">
            @csrf
            <div class="form-group">  
              <label for="nama">Nama :</label>
              <input type="text" id="nama" name="nama" class="form-control" value="<?php echo @$user->nama;?>">
            </div>
            <div class="form-group">  
              <label for="email">Email :</label>
              <input type="email" id="email" name="email" class="form-control" value="<?php echo @$user->email;?>">
            </div>
            <div class="form-group">  
              <label for="kelas">Kelas :</label>
              <input type="text" id="kelas" name="kelas" class="form-control" value="<?php echo @$user->kelas;?>">
            </div>

            <button class="btn btn-md btn-block btn-primary">Simpan</button>
          </form>

        </div>
      </div>
@endcontent