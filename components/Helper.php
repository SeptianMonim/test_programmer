<?php

namespace app\components;

use Yii;
use app\models\Appuser;

class Helper
{
	public static function assert_date($date)
	{
		return date('Y-m-d', strtotime($date));
	}

	/**
	 * 1 - short month
	 * 2 - long month
	 */
	public static function months($lang = 'ID', $format = 1)
	{
		$return = array();
		if (strtoupper($lang) == 'ID') {
			$return[1] = $format == 1 ? 'Jan' : 'Januari';
			$return[2] = $format == 1 ? 'Feb' : 'Februari';
			$return[3] = $format == 1 ? 'Mar' : 'Maret';
			$return[4] = $format == 1 ? 'Apr' : 'April';
			$return[5] = $format == 1 ? 'Mei' : 'Mei';
			$return[6] = $format == 1 ? 'Jun' : 'Juni';
			$return[7] = $format == 1 ? 'Jul' : 'Juli';
			$return[8] = $format == 1 ? 'Ags' : 'Agustus';
			$return[9] = $format == 1 ? 'Sep' : 'September';
			$return[10] = $format == 1 ? 'Okt' : 'Oktober';
			$return[11] = $format == 1 ? 'Nov' : 'November';
			$return[12] = $format == 1 ? 'Des' : 'Desember';
		} elseif (strtoupper($lang) == 'EN') {
			$return[1] = $format == 1 ? 'Jan' : 'January';
			$return[2] = $format == 1 ? 'Feb' : 'February';
			$return[3] = $format == 1 ? 'Mar' : 'March';
			$return[4] = $format == 1 ? 'Apr' : 'April';
			$return[5] = $format == 1 ? 'Mei' : 'May';
			$return[6] = $format == 1 ? 'Jun' : 'June';
			$return[7] = $format == 1 ? 'Jul' : 'July';
			$return[8] = $format == 1 ? 'Ags' : 'August';
			$return[9] = $format == 1 ? 'Sep' : 'September';
			$return[10] = $format == 1 ? 'Oct' : 'October';
			$return[11] = $format == 1 ? 'Nov' : 'November';
			$return[12] = $format == 1 ? 'Dec' : 'December';
		} elseif (strtoupper($lang) == 'ROM') {
			$return[1] = 'I';
			$return[2] = 'II';
			$return[3] = 'III';
			$return[4] = 'IV';
			$return[5] = 'V';
			$return[6] = 'VI';
			$return[7] = 'VII';
			$return[8] = 'VII';
			$return[9] = 'IX';
			$return[10] = 'X';
			$return[11] = 'XI';
			$return[12] = 'XII';
		}
		return $return;
	}

	/**
	 * 1 - dd-mm-yy
	 * 2 - dd-M-yyyy
	 * 3 - d MMM yyyy
	 */
	public static function format_date($date, $format = 1)
	{
		if (date('Y', strtotime($date)) < 2000) {
			return '';
		}
		$return = '';
		if ($format == 1) {
			$return .= date('d-m-Y', strtotime($date));
		} elseif ($format == 2) {
			$mos = self::months();
			$mo = date('n', strtotime($date));
			$return .= date('d-', strtotime($date)) . $mos[$mo] . date('-Y', strtotime($date));
		} elseif ($format == 3) {
			$mos = self::months('ID', 2);
			$mo = date('n', strtotime($date));
			$return .= date('j ', strtotime($date)) . $mos[$mo] . date(' Y', strtotime($date));
		}
		return $return;
	}
	public static function format_decimal($num, $prec = 2, $sep = ',', $dec = ".")
	{
		$int = (int) $num;
		$precision = ($num == $int) ? 0 : $prec;
		return number_format($num, $precision, $dec, $sep);
	}

	public static function calculate_discount($amount, $discount)
	{
		$disc_amount = 0;
		$arrdiscount = explode('+', $discount);
		foreach ($arrdiscount as $disc) {
			$tdiscount = $amount * ($disc / 100);
			$disc_amount += $tdiscount;
			$amount -= $tdiscount;
		}
		return $disc_amount;
	}


	public static function terbilang($angka)
	{
		// pastikan kita hanya berususan dengan tipe data numeric
		$angka = (float)$angka;

		// array bilangan
		// sepuluh dan sebelas merupakan special karena awalan 'se'
		$bilangan = array(
			'',
			'satu',
			'dua',
			'tiga',
			'empat',
			'lima',
			'enam',
			'tujuh',
			'delapan',
			'sembilan',
			'sepuluh',
			'sebelas'
		);

		// pencocokan dimulai dari satuan angka terkecil
		if ($angka < 12) {
			// mapping angka ke index array $bilangan
			return $bilangan[$angka];
		} else if ($angka < 20) {
			// bilangan 'belasan'
			// misal 18 maka 18 - 10 = 8
			return $bilangan[$angka - 10] . ' belas';
		} else if ($angka < 100) {
			// bilangan 'puluhan'
			// misal 27 maka 27 / 10 = 2.7 (integer => 2) 'dua'
			// untuk mendapatkan sisa bagi gunakan modulus
			// 27 mod 10 = 7 'tujuh'
			$hasil_bagi = (int)($angka / 10);
			$hasil_mod = $angka % 10;
			return trim(sprintf('%s puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
		} else if ($angka < 200) {
			// bilangan 'seratusan' (itulah indonesia knp tidak satu ratus saja? :))
			// misal 151 maka 151 = 100 = 51 (hasil berupa 'puluhan')
			// daripada menulis ulang rutin kode puluhan maka gunakan
			// saja fungsi rekursif dengan memanggil fungsi terbilang(51)
			return sprintf('seratus %s', self::terbilang($angka - 100));
		} else if ($angka < 1000) {
			// bilangan 'ratusan'
			// misal 467 maka 467 / 100 = 4,67 (integer => 4) 'empat'
			// sisanya 467 mod 100 = 67 (berupa puluhan jadi gunakan rekursif terbilang(67))
			$hasil_bagi = (int)($angka / 100);
			$hasil_mod = $angka % 100;
			return trim(sprintf('%s ratus %s', $bilangan[$hasil_bagi], self::terbilang($hasil_mod)));
		} else if ($angka < 2000) {
			// bilangan 'seribuan'
			// misal 1250 maka 1250 - 1000 = 250 (ratusan)
			// gunakan rekursif terbilang(250)
			return trim(sprintf('seribu %s', self::terbilang($angka - 1000)));
		} else if ($angka < 1000000) {
			// bilangan 'ribuan' (sampai ratusan ribu
			$hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
			$hasil_mod = $angka % 1000;
			return sprintf('%s ribu %s', self::terbilang($hasil_bagi), self::terbilang($hasil_mod));
		} else if ($angka < 1000000000) {
			// bilangan 'jutaan' (sampai ratusan juta)
			// 'satu puluh' => SALAH
			// 'satu ratus' => SALAH
			// 'satu juta' => BENAR
			// @#$%^ WT*

			// hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
			$hasil_bagi = (int)($angka / 1000000);
			$hasil_mod = $angka % 1000000;
			return trim(sprintf('%s juta %s', self::terbilang($hasil_bagi), self::terbilang($hasil_mod)));
		} else if ($angka < 1000000000000) {
			// bilangan 'milyaran'
			$hasil_bagi = (int)($angka / 1000000000);
			// karena batas maksimum integer untuk 32bit sistem adalah 2147483647
			// maka kita gunakan fmod agar dapat menghandle angka yang lebih besar
			$hasil_mod = fmod($angka, 1000000000);
			return trim(sprintf('%s milyar %s', self::terbilang($hasil_bagi), self::terbilang($hasil_mod)));
		} else if ($angka < 1000000000000000) {
			// bilangan 'triliun'
			$hasil_bagi = $angka / 1000000000000;
			$hasil_mod = fmod($angka, 1000000000000);
			return trim(sprintf('%s triliun %s', self::terbilang($hasil_bagi), self::terbilang($hasil_mod)));
		} else {
			return 'N/A';
		}
	}
	public static function tax()
	{
		$tax = 0.11;
		return $tax;
	}
}
