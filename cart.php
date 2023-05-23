<?php
session_start();
include 'db.php';
if ($_SESSION['status_login'] != true || $_SESSION['type'] != 'user') {
    echo '<script>window.location="masuk.php"</script>';
}
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 1");
$a = mysqli_fetch_object($kontak);
if(isset($_POST['qty'])){
    $update = mysqli_query($conn, "UPDATE tb_cart SET qty = ".$_POST['qty']." WHERE tb_cart.id = ".$_POST['cid']."");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukawarung</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="index.php">Bukawarung</a></h1>
            <ul>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="transaksi.php">Transaksi</a></li>
                <li><a href="keluar.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <div class="section">
        <div class="container">
            <h3>Data Keranjang</h3>
            <div class="box">
                <p><a class="btn" href="produk.php">Tambah Produk</a></p>

                <br>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Total Harga</th>
                            <th>Kuantitas</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_harga = 0;
                        $produk = mysqli_query($conn, "SELECT tb_cart.id, tb_cart.qty, tb_product.product_name, 
                        tb_product.product_price, tb_product.product_image
                        FROM tb_cart 
                        JOIN tb_product ON tb_cart.product_id=tb_product.product_id
                        WHERE tb_cart.user_id = ".$_SESSION['id']."");
                        if (mysqli_num_rows($produk) > 0) {
                            while ($row = mysqli_fetch_array($produk)) {
                                $total_harga +=$row['product_price'] * $row['qty'];
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><a href="produk/<?php echo $row['product_image'] ?>" target="_blank"> 
                                    <img src="produk/<?php echo $row['product_image'] ?>" width="50px"> </a></td>
                                    <td><?php echo $row['product_name'] ?></td>
                                    <td style="width: 150px;">Rp. <?php echo number_format($row['product_price']) ?></td>
                                    <td style="width: 150px;">Rp. <?php echo number_format($row['product_price'] * $row['qty']) ?></td>
                                    <td style="width: 100px;">
                                        <!-- <?php echo $row['qty'] ?> -->
                                        <form action="" method="post">
                                            <select class="input-control" name="qty" onchange='this.form.submit()'>
                                                <option value=1 <?php echo ($row['qty'] == 1)? 'selected':''; ?>>1</option>
                                                <option value=2 <?php echo ($row['qty'] == 2)? 'selected':''; ?>>2</option>
                                                <option value=3 <?php echo ($row['qty'] == 3)? 'selected':''; ?>>3</option>
                                                <option value=4 <?php echo ($row['qty'] == 4)? 'selected':''; ?>>4</option>
                                                <input type="hidden" name="cid" value="<?=$row['id']?>">
                                            </select>
                                        </form>
                                    </td>
                                    <td style="width: 150px;">
                                        <a href="proses-hapus.php?id=<?=$row['id'] ?>" onclick="return confirm('Yakin ingin hapus ?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php }
                            
                        } else { ?>
                            <tr>
                                <td colspan="7">Tidak ada data</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                if (mysqli_num_rows($produk) > 0) {
                ?>
                <br>
                <p>Total Hargra Rp. <?=$total_harga?></p>
                <br>
                <p><a class="btn" href="bayar.php?id=<?=$_SESSION['id']?>">Bayar</a></p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="footer">
        <div class="container">
            <h4>Alamat</h4>
            <p><?php echo $a->admin_address ?></p>

            <h4>Email</h4>
            <p><?php echo $a->admin_email ?></p>

            <h4>No. Hp</h4>
            <p><?php echo $a->admin_telp ?></p>
            <small>Copyright &copy; 2020 - Bukawarung.</small>
        </div>
    </div>
</body>

</html>