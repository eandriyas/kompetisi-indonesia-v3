<br/>
</h3>
<div class="container">
	<div class="row">
		<center><h3 style="text-transform:uppercase" class="kompetisi-detail"><?php echo $this->session->userdata('username')?> dasbor</h3></center>
		<div class="col-md-2" >
			<?php $this->load->view('dashboard/menu');?>
		</div>
		<div style="background-color:#fff" class="col-md-10">
			<h3>Total : <?php echo $total?> </h3>
			<table class="table table-hover">
				<thead>
					<tr>
						<td><strong>No</strong></td>
						<td><strong>Judul Kompetisi</strong></td>
						<td><strong>Kategori</strong></td>
						<td><strong>Penyelenggara</strong></td>
						<td><strong>Deadline</strong></td>
						<td><strong>Pengumuman</strong></td>
						<td><strong>Nilai Total Hadiah</strong></td>
						<td></td>
					</tr>
				</thead>
				<tbody>				
					<?php 
					$n = 1;
					foreach($view as $k) {
						//encode id as url
						$enc = base64_encode(base64_encode($k['id']));
						$id = $id_kompetisi = str_replace('=', '', $enc);
						//judul post
						$judul = str_replace(' ', '-', $k['judul'])
						?>
						<tr>
							<td><?php echo $n?></td>
							<td><?php echo $k['judul']?></td>
							<td>Blog Writing</td>
							<td><?php echo $k['penyelenggara']?></td>
							<?php if($k['deadline'] > 0) { ?>
							<td><?php echo $k['deadline']?> hari lagi</td>
							<?php } else { ?>
							<td>kompetisi telah berakhir </td>
							<?php } ?>
							<td><?php
								if($k['pengumuman'] < 0) {
									echo "Kompetisi Telah Berakhir";
								} else {
									echo $k['pengumuman']."hari lagi";
								}
								?></td>
								<td>Rp.<?php echo $k['total']?>,-</td>
								<td><a target="_blank" href="<?php echo site_url().'/kompetisi/detail/'.$id.'/'.$judul?>" class="btn btn-default"><span class="glyphicon glyphicon-chevron-right"></span></td>
							</tr>
							<?php
							$n++;
						} ?>
					</tbody>
				</table>
			</br>
			<div style="padding:10px; border:2px solid #E6E6E6; font-size:15px"><center><?php echo $page ?> </center></div>
		</br>
	</div>
</div>
</div>
<br/>