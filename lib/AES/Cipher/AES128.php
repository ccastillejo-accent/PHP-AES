<?php

namespace AES\Cipher;

class AES128 extends AESCipher
{
    protected $eStop = 36;
    protected $dStart = 43;

    function setKey($key)
    {
        $t0 = $this->T0i;
        $t1 = $this->T1i;
        $t2 = $this->T2i;
        $t3 = $this->T3i;
        $s = $this->S;

        list(,$rk0, $rk1, $rk2, $rk3) = unpack('N4', $key);

        $rk = $rki = [$rk0, $rk1, $rk2, $rk3];

        for ($i = 4, $rc = 1; $i < 40; $rc = ($rc << 1) % 0xe5) {
            $rk[$i] = $rk0 = $rk0 ^ ($s[$rk3 >> 24 & 0xff] | ($s[$rk3 & 0xff] << 8) | ($s[$rk3 >> 8 & 0xff] << 16) | (($s[$rk3 >> 16 & 0xff] ^ $rc) << 24));
            $rki[$i++] = $t0[$s[$rk0 >> 24 & 0xff]] ^ $t1[$s[$rk0 >> 16 & 0xff]] ^ $t2[$s[$rk0 >> 8 & 0xff]] ^ $t3[$s[$rk0 & 0xff]];
            $rk[$i] = $rk1 = $rk1 ^ $rk0;
            $rki[$i++] = $t0[$s[$rk1 >> 24 & 0xff]] ^ $t1[$s[$rk1 >> 16 & 0xff]] ^ $t2[$s[$rk1 >> 8 & 0xff]] ^ $t3[$s[$rk1 & 0xff]];
            $rk[$i] = $rk2 = $rk2 ^ $rk1;
            $rki[$i++] = $t0[$s[$rk2 >> 24 & 0xff]] ^ $t1[$s[$rk2 >> 16 & 0xff]] ^ $t2[$s[$rk2 >> 8 & 0xff]] ^ $t3[$s[$rk2 & 0xff]];
            $rk[$i] = $rk3 = $rk3 ^ $rk2;
            $rki[$i++] = $t0[$s[$rk3 >> 24 & 0xff]] ^ $t1[$s[$rk3 >> 16 & 0xff]] ^ $t2[$s[$rk3 >> 8 & 0xff]] ^ $t3[$s[$rk3 & 0xff]];
        }

        $rk[40] = $rki[40] = $rk0 = $rk0 ^ ($s[$rk3 >> 24 & 0xff] | ($s[$rk3 & 0xff] << 8) | ($s[$rk3 >> 8 & 0xff] << 16) | (($s[$rk3 >> 16 & 0xff] ^ 0x36) << 24));
        $rk[41] = $rki[41] = $rk1 = $rk1 ^ $rk0;
        $rk[42] = $rki[42] = $rk2 = $rk2 ^ $rk1;
        $rk[43] = $rki[43] = $rk3 ^ $rk2;

        $this->RK = $rk;
        $this->RKi = $rki;
    }
}
