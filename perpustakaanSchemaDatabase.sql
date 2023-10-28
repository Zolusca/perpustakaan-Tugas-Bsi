CREATE DATABASE perpustakaan;

CREATE TABLE `user` (
  `id_user` varchar(128) NOT NULL,
  `nama` varchar(128) NOT NULL,
  `alamat` text,
  `email` varchar(128) NOT NULL,
  `gambar` varchar(128) DEFAULT (_utf8mb4'blankprofile.jpg'),
  `password` varchar(128) NOT NULL,
  `role_user` enum('anggota','admin') NOT NULL,
  `is_active` enum('active','inctive') DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB; 

CREATE TABLE `buku` (
  `id_buku` varchar(128) NOT NULL,
  `judul_buku` varchar(128) NOT NULL,
  `id_kategori` varchar(128) DEFAULT NULL,
  `pengarang` varchar(128) DEFAULT NULL,
  `penerbit` varchar(64) DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `isbn` varchar(64) DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `dipinjam` int DEFAULT NULL,
  `dibooking` int DEFAULT NULL,
  `gambar` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_buku`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_buku` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `kategori_buku` (
  `id_kategori` varchar(120) NOT NULL,
  `nama_kategori` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `nama_kategori` (`nama_kategori`)
) ENGINE=InnoDB;

CREATE TABLE `temp` (
  `id_temp` int NOT NULL AUTO_INCREMENT,
  `tgl_booking` date DEFAULT NULL,
  `id_user` varchar(128) NOT NULL,
  `id_buku` varchar(128) NOT NULL,
  PRIMARY KEY (`id_temp`),
  KEY `id_user` (`id_user`),
  KEY `id_buku` (`id_buku`),
  CONSTRAINT `temp_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `temp_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `booking` (
  `id_booking` varchar(128) NOT NULL,
  `tgl_booking` date NOT NULL,
  `batas_ambil` date NOT NULL,
  `id_user` varchar(128) NOT NULL,
  PRIMARY KEY (`id_booking`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `booking_detail` (
  `id` varchar(128) NOT NULL,
  `id_booking` varchar(128) DEFAULT NULL,
  `id_buku` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_booking` (`id_booking`),
  KEY `id_buku` (`id_buku`),
  CONSTRAINT `booking_detail_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `booking_detail_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `pinjam` (
  `no_pinjam` varchar(64) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `id_booking` varchar(128) NOT NULL,
  `id_user` varchar(128) NOT NULL,
  `tgl_kembali` date NOT NULL,
  `tgl_pengembalian` date DEFAULT NULL,
  `status` enum('dikembalikan','dipinjam') DEFAULT (_utf8mb4'pinjam'),
  `total_denda` decimal(30,0) NOT NULL,
  PRIMARY KEY (`no_pinjam`),
  KEY `id_booking` (`id_booking`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `pinjam_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pinjam_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `detail_pinjam` (
  `no_pinjam` varchar(64) DEFAULT NULL,
  `id_buku` varchar(128) DEFAULT NULL,
  `denda` decimal(30,0) DEFAULT NULL
) ENGINE=InnoDB;



