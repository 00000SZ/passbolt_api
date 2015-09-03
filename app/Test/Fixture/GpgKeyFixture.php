<?php
/**
 * GpgkeyFixture
 *
 */
class GpgkeyFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 4096, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'bits' => array('type' => 'integer', 'null' => false, 'default' => '2048', 'unsigned' => false),
		'uid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'key_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 8, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'fingerprint' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 51, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'expires' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'key_created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'created_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'modified_by' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '55d1c995-04bc-47e8-9bdb-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFRso0cBCAC+J/b4LoML0L9/xlIs3/TZKC9CSVTQ2xljs3hdawvGi/+p210M
doXev6optgaDPj0q61HaCR1XhrCa7gK9jEC54M91LwrRzm5nBT9Fez/wezXn2I0v
56RIAn42k3OcDwWUDdPenzZS+/4/efJPyb/XO7sZMiD+OjjpXwNNu9ezqSvNZ1uo
/VcMHBTkQ0NqETO5Yt5KX9JkrKP2Q0BR2BVHGHp7K/PJiWnN+T8dTFr6RsiZsVWs
dD/5IPSkNAsi8E8fguuWecQtMftled/36QjlaXYgZ/U1kVi2mDUebd6oxTvB85fm
pCvIekFRNqs6TAd4de+pDBsbYY+vsE1tCsxvABEBAAG0JFBhc3Nib2x0IFBHUCA8
cGFzc2JvbHRAcGFzc2JvbHQuY29tPokBPQQTAQoAJwUCVGyjRwIbAwUJB4YfgAUL
CQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRBPgZQCX9LZLAk6CACop+n6hgaCrFWU
m5EaT2+XBBw9rEbcISCH8Zeh2Xk1RmLOiTLSYRka8qnUcEBbSq8EOoJsfNdWEK8d
QwhearHZjRCUjrQMPsMwwKhKrkG7RR7VI+hN+7H7Joyq3UDE7S+55vvWd7hSZbPl
buhPWBirviN1Lovk2tZbI7ClW1+Cx9uK3lad1LywlPsxkCKbRfDcWrnLFKk1UnYi
229ZXCYjuJbzfPRWx039nVVt6IoOZnLCil5G9d5AFt5Ro7WFdormTsfP+EehLI7q
szrEVD2ZQgn+rSF8P97DLABDa28+JfTsnivVQn5cyLR6x+XTJp96SSprm5nY0C3+
ybog/dDFuQENBFRso0cBCAC50ryBhhesYxrJEPDvlK8R0E8zCxv7I6fXXgORNyAW
PAsZBUsaQizTUsP9VpO6Y0gOPGxvcGP9xSc+01n1stM9S7/+utCfm8yD4UtP9Ric
mkq/T/w/l9iLFypo6al47HW28mQlMvbUWSkMoK9JXRpB2c2VPmN8UXVQX4cQ++ad
YQNnRgSo3n+VdvIKgSW3rkcQIriGX3P79cciqAA/NzkivNyZSQaVBLJioO+kDkYu
Q+oIstvEusmHIon0Ltggi8B6LM5vAQpBRwQ9dfUgAbpQpfzm8VUkCGmsUr5hnOO3
tmaWOTKZcpXiF5+rW2NrqiAhRhm44s+JipmTE++u/6X9ABEBAAGJASUEGAEKAA8F
AlRso0cCGwwFCQeGH4AACgkQT4GUAl/S2Sx2LQgAoXOxfA5pOCm9UP2f2pQA7hyv
DEppROxkBLVcnZdpVFw4yrVQh/IWHSxcX0rcrTPlBjjFpTos+ACOZ5EKSRCHjIqF
biraG5/2YjKa5cqc7z/W9bSuhmWizPBpXlQk6MohG6jXlw7OyVosisbHGobFa5CW
hF+Kc8tb0mvk9vmqn/eDYnGYcSftapyGB3lq7w4qtKzlvn2g2FlnxJCdnrG3zGtO
Kqusl1GcnrNFuDDtDwZS1G+3T8Y8ZH8tRnTwrSeO3I7hw/cdzCEDg4isqFw371vz
UghWsISL244Umc6ZmTufAs+7/6sNNzFAb5SzwVmpLla1x3jth4bwLcJTGFq/vw==
=GG/Z
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2048',
			'uid' => 'Passbolt PGP <passbolt@passbolt.com>',
			'key_id' => '5FD2D92C',
			'fingerprint' => '120F87DDE5A438DE89826D464F8194025FD2D92C',
			'type' => 'RSA',
			'expires' => '2018-11-19 15:03:51',
			'key_created' => '2014-11-19 15:03:51',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c995-09a0-470b-bece-4741c0a80111',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VhJINUw1ojbw3d8=
=5xjf
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Edith Clarke <edith@passbolt.com>',
			'key_id' => '9E67396C',
			'fingerprint' => 'D5FDE007B7B4B9816ECE25F61D67BAA69E67396C',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:47:27',
			'key_created' => '2015-07-03 12:47:27',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e'
		),
		array(
			'id' => '55d1c995-2d2c-444c-b38f-4741c0a80111',
			'user_id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
1RIzS4UqhfIPH7vdEAQ=
=GnKL
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Marlyn Wescoff <marlyn@passbolt.com>',
			'key_id' => '0735B1C0',
			'fingerprint' => 'E4400EE5E49B86B96FB7D7F48BB0AD490735B1C0',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:58:34',
			'key_created' => '2015-07-03 12:58:34',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
			'modified_by' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-2fdc-4a46-9ae4-4741c0a80111',
			'user_id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
4Hr4x+mhfw==
=PxBV
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4093',
			'uid' => 'User Test <user@passbolt.com>',
			'key_id' => 'C9D7E7AE',
			'fingerprint' => '1518D8673F353A65A8C3F412615186AFC9D7E7AE',
			'type' => 'RSA',
			'expires' => '2019-07-02 12:06:10',
			'key_created' => '2015-07-02 12:06:10',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-38f8-41df-9ef5-4741c0a80111',
			'user_id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQENBFWK2WIBCADVs92u7xQkFpXwV/ShpQ89hA7HJz0tRTSGi57+SWIZOChKFYlT
kzKqVYoSQa9nTAAnkiO6mDqaBBNx9xtV1q2ITGGz45U3oYm1+1u7BAeGQCeZA4az
Qqfk6+vRq+lBZk6v7rBRqH+mN+CTsink20eygSl+u5YwKdcH6KbYaYMqj+6VTCTm
JN/H+xTQpJbxQfm/pq1ms9GnsYgbdEpOH+NnO23d3HCSMDE2xPWVfF3P71VhD+hg
yW3fLKRZwlMGombGf81YFnoeZWMYjfgZaMAxPapyOQZ/5C6X8TUFAhrf0UTtHLAY
H4XSct4zdV3cGJKqvKY6lIvowWAt0xe6vJQnABEBAAG0M2FkYSBsb3ZlbGFjZSAo
cGFzc2JvbHQgZGVtbyBrZXkpIDxhZGFAcGFzc2JvbHQuY29tPokBNwQTAQoAIQUC
VYrZYgIbAwULCQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRAv6WtHx/9CGuBeB/9L
FN2gW0DCsd8099Bjow7NVwgh0+KUKkzlXpOBvn6/qkamzcq+CX4XSaux/F0uU6fj
Y0vsObe6kXGS8RyXpB3Itx/gwZ7R1mwMZVlKypeqDme3P2AnP+HRpomafHqsKs2I
clipwoAS9EwhMer/2QxQMbHp6h50qMxDHoMhnDGmOq5Zcb9Hy8/qHAolTgnk/Y8A
n/9riyzPvDcr+2vsZp3RYOvPxZZPjqwPR3FNQURpyYdhEGl51kpyM9Dc6lwKPA/c
YNVgSHbGLeQd/L6Dfc1eqZI/4GKst1ll734JJOQvYmBnLwVO1FrdvUJDuDn9br5U
IUmFnsvqVv4bUNJvl232uQENBFWK2WIBCADBLSzTzxqY9Lf3+OcHZIc45BnTaZzM
ZnOVJH9wccDeZczDKNEe4xpBdhQtrk8Lre3hcV80pm4jineo7yJtxsHKPejkzhRm
uJSA344mO8SSYMJeN4fyIY+TMwZts+trSi8G0Mw34jVA19JLSnctzM+qP2FK2HLS
RyS7joxblx7MfM5SW5s6NBW1wihMF2xfSYuukc62uzCksYFG1vjVNgdsWqO/d1bH
IVCxnu0a93ElnxR9EtQoTmdKflOMQaJmLWTDTgjZUe12y5uSc0CJVyhJ3Vxf+sqH
rDKdttKE5F2jcKkF6L9nihrrOulyFnm7JDZG09izJhQrjy8+F41/QswpABEBAAGJ
AR8EGAEKAAkFAlWK2WICGwwACgkQL+lrR8f/QhrI3wgApkoy09u13miDLsPJFO4g
2LWbdJBnC+K7K+BiJAab08Q5fDzxF+U02bBwq1z6TWJzKOseW6nBZZnYERteCNxZ
ljiRkcux42hU0hoH3GuZRjj2N+lCupsrmWNo4I2+pRijbdITQT3v8iba3vTrfqrC
+3b+X45qIQKSePcGIhjCeuFZtMamUISCx+O49ltCa+VtToHTC+NqCYi4N8TbS0ad
AIhiF+HjVjLgVYwcBoRxBdEgSdI9789aMpLhjrypV3ARc0JsNjmZiAGCjqPObkVP
ef1dHv+uAIXKttVBEgwjZs9c3Xx7m413yCIj5q5DH213Hne+We0At1l9eijACl+Y
7w==
=TcyZ
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2047',
			'uid' => 'ada lovelace (passbolt demo key) <ada@passbolt.com>',
			'key_id' => 'C7FF421A',
			'fingerprint' => '333788B5464B797FDF10A98F2FE96B47C7FF421A',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2015-06-24 18:22:58',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'modified_by' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-3a60-4e53-b48d-4741c0a80111',
			'user_id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
a09IIZln6LLZOig=
=jibv
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Grace Hopper <grace@passbolt.com>',
			'key_id' => '9BC6A569',
			'fingerprint' => '63452C7A0AE6FAE8C8C309640BD9E2409BC6A569',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:49:21',
			'key_created' => '2015-07-03 12:49:21',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
			'modified_by' => '50cdea9c-f214-4549-9807-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-3b10-460a-b8cf-4741c0a80111',
			'user_id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
JqiVwWjaIO3D
=7Goz
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4094',
			'uid' => 'Lynn Jolitz <lynn@passbolt.com>',
			'key_id' => '8EA58CFC',
			'fingerprint' => 'B5D364ECDAB5B3F79C6879B85F64D13E8EA58CFC',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:56:50',
			'key_created' => '2015-07-03 12:56:50',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
			'modified_by' => '533d32c0-1f30-438c-8f26-1768c0a895dc'
		),
		array(
			'id' => '55d1c995-3e64-4bd7-a38c-4741c0a80111',
			'user_id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
lHRL/q8pHZye
=2YrD
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Jean Bartik <jean@passbolt.com>',
			'key_id' => '24AA1193',
			'fingerprint' => '8F758E3BDD8445361A8A6AD073BAC28524AA1193',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:54:25',
			'key_created' => '2015-07-03 12:54:25',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-5210-4a1e-9d16-4741c0a80111',
			'user_id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFRso0cBCAC+J/b4LoML0L9/xlIs3/TZKC9CSVTQ2xljs3hdawvGi/+p210M
doXev6optgaDPj0q61HaCR1XhrCa7gK9jEC54M91LwrRzm5nBT9Fez/wezXn2I0v
56RIAn42k3OcDwWUDdPenzZS+/4/efJPyb/XO7sZMiD+OjjpXwNNu9ezqSvNZ1uo
/VcMHBTkQ0NqETO5Yt5KX9JkrKP2Q0BR2BVHGHp7K/PJiWnN+T8dTFr6RsiZsVWs
dD/5IPSkNAsi8E8fguuWecQtMftled/36QjlaXYgZ/U1kVi2mDUebd6oxTvB85fm
pCvIekFRNqs6TAd4de+pDBsbYY+vsE1tCsxvABEBAAG0JFBhc3Nib2x0IFBHUCA8
cGFzc2JvbHRAcGFzc2JvbHQuY29tPokBPQQTAQoAJwUCVGyjRwIbAwUJB4YfgAUL
CQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRBPgZQCX9LZLAk6CACop+n6hgaCrFWU
m5EaT2+XBBw9rEbcISCH8Zeh2Xk1RmLOiTLSYRka8qnUcEBbSq8EOoJsfNdWEK8d
QwhearHZjRCUjrQMPsMwwKhKrkG7RR7VI+hN+7H7Joyq3UDE7S+55vvWd7hSZbPl
buhPWBirviN1Lovk2tZbI7ClW1+Cx9uK3lad1LywlPsxkCKbRfDcWrnLFKk1UnYi
229ZXCYjuJbzfPRWx039nVVt6IoOZnLCil5G9d5AFt5Ro7WFdormTsfP+EehLI7q
szrEVD2ZQgn+rSF8P97DLABDa28+JfTsnivVQn5cyLR6x+XTJp96SSprm5nY0C3+
ybog/dDFuQENBFRso0cBCAC50ryBhhesYxrJEPDvlK8R0E8zCxv7I6fXXgORNyAW
PAsZBUsaQizTUsP9VpO6Y0gOPGxvcGP9xSc+01n1stM9S7/+utCfm8yD4UtP9Ric
mkq/T/w/l9iLFypo6al47HW28mQlMvbUWSkMoK9JXRpB2c2VPmN8UXVQX4cQ++ad
YQNnRgSo3n+VdvIKgSW3rkcQIriGX3P79cciqAA/NzkivNyZSQaVBLJioO+kDkYu
Q+oIstvEusmHIon0Ltggi8B6LM5vAQpBRwQ9dfUgAbpQpfzm8VUkCGmsUr5hnOO3
tmaWOTKZcpXiF5+rW2NrqiAhRhm44s+JipmTE++u/6X9ABEBAAGJASUEGAEKAA8F
AlRso0cCGwwFCQeGH4AACgkQT4GUAl/S2Sx2LQgAoXOxfA5pOCm9UP2f2pQA7hyv
DEppROxkBLVcnZdpVFw4yrVQh/IWHSxcX0rcrTPlBjjFpTos+ACOZ5EKSRCHjIqF
biraG5/2YjKa5cqc7z/W9bSuhmWizPBpXlQk6MohG6jXlw7OyVosisbHGobFa5CW
hF+Kc8tb0mvk9vmqn/eDYnGYcSftapyGB3lq7w4qtKzlvn2g2FlnxJCdnrG3zGtO
Kqusl1GcnrNFuDDtDwZS1G+3T8Y8ZH8tRnTwrSeO3I7hw/cdzCEDg4isqFw371vz
UghWsISL244Umc6ZmTufAs+7/6sNNzFAb5SzwVmpLla1x3jth4bwLcJTGFq/vw==
=GG/Z
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2048',
			'uid' => 'Passbolt PGP <passbolt@passbolt.com>',
			'key_id' => '5FD2D92C',
			'fingerprint' => '120F87DDE5A438DE89826D464F8194025FD2D92C',
			'type' => 'RSA',
			'expires' => '2018-11-19 15:03:51',
			'key_created' => '2014-11-19 15:03:51',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55d1c995-5f6c-45ed-8ae6-4741c0a80111',
			'user_id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
t8bJrYvwZzYi
=Wbeg
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '4096',
			'uid' => 'Carol Shaw <carol@passbolt.com>',
			'key_id' => '82945D3E',
			'fingerprint' => '57DE7D79ABE733A235EB1F84CDF8FC8682945D3E',
			'type' => 'RSA',
			'expires' => '2019-07-02 13:25:12',
			'key_created' => '2015-07-02 13:25:12',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
			'modified_by' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-6124-4560-bfba-4741c0a80111',
			'user_id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFRso0cBCAC+J/b4LoML0L9/xlIs3/TZKC9CSVTQ2xljs3hdawvGi/+p210M
doXev6optgaDPj0q61HaCR1XhrCa7gK9jEC54M91LwrRzm5nBT9Fez/wezXn2I0v
56RIAn42k3OcDwWUDdPenzZS+/4/efJPyb/XO7sZMiD+OjjpXwNNu9ezqSvNZ1uo
/VcMHBTkQ0NqETO5Yt5KX9JkrKP2Q0BR2BVHGHp7K/PJiWnN+T8dTFr6RsiZsVWs
dD/5IPSkNAsi8E8fguuWecQtMftled/36QjlaXYgZ/U1kVi2mDUebd6oxTvB85fm
pCvIekFRNqs6TAd4de+pDBsbYY+vsE1tCsxvABEBAAG0JFBhc3Nib2x0IFBHUCA8
cGFzc2JvbHRAcGFzc2JvbHQuY29tPokBPQQTAQoAJwUCVGyjRwIbAwUJB4YfgAUL
CQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRBPgZQCX9LZLAk6CACop+n6hgaCrFWU
m5EaT2+XBBw9rEbcISCH8Zeh2Xk1RmLOiTLSYRka8qnUcEBbSq8EOoJsfNdWEK8d
QwhearHZjRCUjrQMPsMwwKhKrkG7RR7VI+hN+7H7Joyq3UDE7S+55vvWd7hSZbPl
buhPWBirviN1Lovk2tZbI7ClW1+Cx9uK3lad1LywlPsxkCKbRfDcWrnLFKk1UnYi
229ZXCYjuJbzfPRWx039nVVt6IoOZnLCil5G9d5AFt5Ro7WFdormTsfP+EehLI7q
szrEVD2ZQgn+rSF8P97DLABDa28+JfTsnivVQn5cyLR6x+XTJp96SSprm5nY0C3+
ybog/dDFuQENBFRso0cBCAC50ryBhhesYxrJEPDvlK8R0E8zCxv7I6fXXgORNyAW
PAsZBUsaQizTUsP9VpO6Y0gOPGxvcGP9xSc+01n1stM9S7/+utCfm8yD4UtP9Ric
mkq/T/w/l9iLFypo6al47HW28mQlMvbUWSkMoK9JXRpB2c2VPmN8UXVQX4cQ++ad
YQNnRgSo3n+VdvIKgSW3rkcQIriGX3P79cciqAA/NzkivNyZSQaVBLJioO+kDkYu
Q+oIstvEusmHIon0Ltggi8B6LM5vAQpBRwQ9dfUgAbpQpfzm8VUkCGmsUr5hnOO3
tmaWOTKZcpXiF5+rW2NrqiAhRhm44s+JipmTE++u/6X9ABEBAAGJASUEGAEKAA8F
AlRso0cCGwwFCQeGH4AACgkQT4GUAl/S2Sx2LQgAoXOxfA5pOCm9UP2f2pQA7hyv
DEppROxkBLVcnZdpVFw4yrVQh/IWHSxcX0rcrTPlBjjFpTos+ACOZ5EKSRCHjIqF
biraG5/2YjKa5cqc7z/W9bSuhmWizPBpXlQk6MohG6jXlw7OyVosisbHGobFa5CW
hF+Kc8tb0mvk9vmqn/eDYnGYcSftapyGB3lq7w4qtKzlvn2g2FlnxJCdnrG3zGtO
Kqusl1GcnrNFuDDtDwZS1G+3T8Y8ZH8tRnTwrSeO3I7hw/cdzCEDg4isqFw371vz
UghWsISL244Umc6ZmTufAs+7/6sNNzFAb5SzwVmpLla1x3jth4bwLcJTGFq/vw==
=GG/Z
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '2048',
			'uid' => 'Passbolt PGP <passbolt@passbolt.com>',
			'key_id' => '5FD2D92C',
			'fingerprint' => '120F87DDE5A438DE89826D464F8194025FD2D92C',
			'type' => 'RSA',
			'expires' => '2018-11-19 15:03:51',
			'key_created' => '2014-11-19 15:03:51',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'modified_by' => '533d3564-03e8-4963-94a7-178cc0a895dc'
		),
		array(
			'id' => '55d1c995-74a4-4949-83fe-4741c0a80111',
			'user_id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VX5VSIUYQFVe
=UUaB
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Hedy Lamarr <hedy@passbolt.com>',
			'key_id' => 'A812A61E',
			'fingerprint' => 'ED39FA1D15C0B2A81359A872928088DAA812A61E',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:51:31',
			'key_created' => '2015-07-03 12:51:31',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
			'modified_by' => '533d37a0-aa11-4945-9b11-1663a0a895dc'
		),
		array(
			'id' => '55d1c995-79e4-4594-bc39-4741c0a80111',
			'user_id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
b7c81OKzWqN3kkNCTtycnemmZRE=
=f88E
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Kathleen Antonelli <kathleen@passbolt.com>',
			'key_id' => 'A73AE279',
			'fingerprint' => '14D07AFFDE916BC904F17AFB4D203642A73AE279',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:55:55',
			'key_created' => '2015-07-03 12:55:55',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
			'modified_by' => 'eeee6042-c5cd-11e1-a0c5-080027796c51'
		),
		array(
			'id' => '55d1c995-7ad0-43c7-a355-4741c0a80111',
			'user_id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
gJf4InpB2dxOL4K2Z7c=
=W+0N
-----END PGP PUBLIC KEY BLOCK-----',
			'bits' => '4094',
			'uid' => 'Betty Holberton <betty@passbolt.com>',
			'key_id' => 'E61D7009',
			'fingerprint' => 'A754860C3ADE5AB04599025ED3F1FE4BE61D7009',
			'type' => 'RSA',
			'expires' => '2019-07-02 13:28:17',
			'key_created' => '2015-07-02 13:28:17',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
			'modified_by' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-7ec8-418f-9fe7-4741c0a80111',
			'user_id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
LhhailjVzD+qRWUEbzscp1Ih
=WyEV
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Dame \'Steve\' Shirley <dame@passbolt.com>',
			'key_id' => '959CF1D0',
			'fingerprint' => '03E6535C52AFD7544C555829DD8E26DB959CF1D0',
			'type' => 'RSA',
			'expires' => '2019-07-02 13:58:41',
			'key_created' => '2015-07-02 13:58:41',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'dada6042-c5cd-11e1-a0c5-080027796c4c'
		),
		array(
			'id' => '55d1c995-c294-4fd3-8047-4741c0a80111',
			'user_id' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

mQINBFWWaH8BEADaNmNDTAuy9QRsdFTV1yJSbI6u5GYuDWV6TS7isEFxj+BIvgAc
ryRjXfUHJv/WOC1O4lCS5sOvYxwVTsafY6U4qqEJZa2SO+1GxC5Gdty+G6pVnkw6
9Zh4RUErKKQYR9qCKyHBDMcEnDHZv4KMRMhwgrihWWyfOgdIkgv7PESsGTJIzZ7q
62ylAPHRdF7BGFn6WUJbH75NIxpybY8mRuVM/5rCbn1zxzHiUSR2V8jjjVSZIrye
oJnXuP7ZCG8GkJxRPX0wu5q+2gumczeWBLkFN2+X3wf0y/K1kn9wB4TFTfpEGxIU
aJ6yhwCS48b6NDG6rENth1idzbu0Q9lKqNxJ8v24bQ2tZsO6qGFxvqA4eCaW+tx1
182oq4Akmi2Oon/ryU5OFoLObhDI9uFYkSh5EOS6DefcXMwcUZT9Wvy4DA/6gqSj
o26lZiqGZ77PtTPB876wHWPyrwiDgTdkaOYdvpx95AnUcQtkgh7n0kCkMEHLP5kc
NEIoJzbu2UKZ6nxMG/gMD2kX1anSdI2MJXGdEQO4bX4Do3UeiOyHzXzqe3YC+l3d
c5F8Nqug/GiRHGEex3FOEEUHGhzSrOcf0QKAjtK9pfZicrUjLMeQC7veXp/Hfut4
uxhl1CtEXMhK/FIVjNV25gaoA8aZUiw4mb+dnIgIzj7n+B/aPWurlsE/iQARAQAB
tCRGcmFuY2VzIEFsbGVuIDxmcmFuY2VzQHBhc3Nib2x0LmNvbT6JAj0EEwEKACcF
AlWWaH8CGwMFCQeGH4AFCwkIBwMFFQoJCAsFFgIDAQACHgECF4AACgkQ6NxWF0d/
sUzSMg//YBhJSS9S/k52m49y6Q40FSKk+5FHcO8VdFzKumqIAw9XMQBXmx2+YuGP
qFvev8419BqVJyZjfIlk3giImNCtWF+mSGxd7RYrwFpp82WVOJWcA03MTQc3fa/6
RTLooSL3pgNjfOxTo5qWY0xDhgqk/gCoQJ+bux6iITr09pqlvnmtQyZE4tHwxRpb
YBTHd39wyI2dx4XG6CNDkVzevdyYgAxwhGiM6LEHRpi9ZGw6Yj3q1jIQzoyRlAkh
LPeTr20zCY+w1/Rd67ORc+YhLb4fygg24D2D6jybYLS4txyjnuknMMK1azYQlyre
mssoUf/O78KJ2N9ieuZoU6aAP3YCvkZI4bfxSYarQHqQDKpcoBRrWd1xaH4ceA5f
4BrHN2NrTkrEs3OOg1mPhRVFHB4Y9eTSvdRJ/bmu07fg4NPzgTqgpGKQa9q676Lj
FYNx7P+6LJglvmG4r9vPPmlhEnni4Ctio+gCeyZMFZjmo9DsgIx0ypNmiJLZzolk
7W8wlEdjyJzMoWYuf4Rco8k3nZCE4VbeSHCkc6rRsPV7d6kDNW/Iy1sVN/LeHMa5
l+HCAuGl0TdlHQi1zelUyY9Tgh65lkzUVGRXl3Qxtu625kB9SGSSTNc7HtHB2Qmy
2RNuVOx4PSXkXf1nL3Kv2EeMIeoOG2opVBrept1rh3eOVHYdHDK5Ag0EVZZofwEQ
ALMDoKb2p9e2XmEfJ6+bCgbDJFiiPz8fK/nQWztsUgVzHYWlCo2xBz1J9J9Nxaag
0bVthFsUaUlnFxUF6lxDe6YVF6lR7Ck+BJ6etSd7vNkaDI/H6NC8XHt1jvFm2CaY
9fi4bwr7baCWqowd2IsLWJ+1Pdg2S4RIM1027hjkqwsEP3OczqpwKaJpw7nS+DN+
LHjbZ/w8GH13Q7h1XGDSgQ0iVmmTlWJG724BW4IzH8EZwLdbsgV9Stm5pbH778Oo
lduhHBjhQdi8sJVQGZ/pyIG1qvqgUvbmkWV2JiitiGogCiueokU4P7eduIf5buSk
mYNVLuot0ft4IbjL/mOKXG0hNi/qrNLOWsHeuscAaxWaQS8FEj1BLjq2UvR0WOJy
5hG0JDrksJko0HCTR61SIAiliGrdrQeb+pI1GO6cjuxo/FeWwr+nsIJkk81vqD84
o2PkIe/ofiE3Xx2b0VptXF2iI9BS9wL1Vz8TKM/f9D6h/0LgFtYxDyblpYXbyfwD
t/qzuXw0fvOF5uBYvwbtXAVSc+X2qf7iWXu2SJ+ue2eyVwHdlgkPRRxolhK543cR
ACKeSh4L6NhTo7186KAv/1uLiPJMdNNYqttglPGAXV+7pSpkfrATE0/m4pskYTJd
nYDhPSBKMw2ofGOA+5nC0iXguQ4cLu3D1YFSnHR1+HlbABEBAAGJAiUEGAEKAA8F
AlWWaH8CGwwFCQeGH4AACgkQ6NxWF0d/sUyVbQ//RQc7ovI0XZDVbufE8utyHKmd
GwMb+u9LwJ0uUSWIexhEPgdbYbFrOTjHviMWDhOEbZUld7ceEmXaPLsz10/a8KRL
n3vScyl8RR2A0lpjQQ6evS8z5WGxvvl3DBNxO/QRw7EoXcy+UmpFj+U1khADxfd4
qwncJOL9JT8yJJKy7AFNg6D2J8K2d0Noz07N5JSUFVdwTFeuo+UyoHu9lWvRRHg8
z1tdcxrxkKFvBoaIQPBP1MA/lFenX4mGDhY1YZ6z8J0J3TYrPrnGXTCqkYNSG1ho
sW9jgFi3QvQ/r49Fne5jgMyyXjurydrYtZVQj08cyKfA6Ho7D+S/UWp0IxyvYv/a
u90V+5g9OODatgffOnU2o/B08cqkB9MFkqCEMCqZDScLj5o/GUDqA296IiTOAyDO
WRDjevwnX85Y2z6Dj73fhs0e7eMN0owFIKQfCb0ROR4fOM6AD50IPa6HJgiiK6IN
vcHyHH3sjPdEhqUDa8RPcFdiU9r2Qz8lew58ySKUKAWkcuNLUcV64jSWJtgjq4WD
Pr7+4czeGsvWvzBWKZn+jjKP/pjxbhCRCQ3D6BiC7eJRxNnWmAislLW9DQFLf++J
WD0Nyhbu8SQk/KAHiUXdoflOB7w9bQG3df6P025Bf4xvkVsIZUn0a+7AoMNC2ce1
BzXzQTAO+Vo1Nkyf7B8=
=vRc1
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4095',
			'uid' => 'Frances Allen <frances@passbolt.com>',
			'key_id' => '477FB14C',
			'fingerprint' => '98DA33350692F21BD5F83A17E8DC5617477FB14C',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:48:31',
			'key_created' => '2015-07-03 12:48:31',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '533d346d-d378-4acc-affd-1663c0a895dc',
			'modified_by' => '533d346d-d378-4acc-affd-1663c0a895dc'
		),
		array(
			'id' => '55d1c995-c7d8-4086-9830-4741c0a80111',
			'user_id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
VX5VSIUYQFVe
=UUaB
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '4096',
			'uid' => 'Hedy Lamarr <hedy@passbolt.com>',
			'key_id' => 'A812A61E',
			'fingerprint' => 'ED39FA1D15C0B2A81359A872928088DAA812A61E',
			'type' => 'RSA',
			'expires' => '2019-07-03 12:51:31',
			'key_created' => '2015-07-03 12:51:31',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
			'modified_by' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce'
		),
		array(
			'id' => '55d1c995-e198-45f5-bf96-4741c0a80111',
			'user_id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFUMD/0BCAC4tE1zGczJNVgoEcwqSyJpjhOkRq2IlHSF/jncoKlHz88zZ+kj
4ChZAQCefzXuyAbvinxGcOeP4PqS25+0x7MKDxvZqKltWFvWXt5pG2dCT+UPJnfL
TLBvkLIy1k3f/qgvraQB0ih0GnDHGXwPkozl7Dwjxn7DnbqqmcIXaDlqfTgUKgu0
ry1FyakTO8tQz+jTYSBdxgxoxWtEEoScuvo79O4GD3TLw3Q+Hf/Hem4jMkeEJ4EQ
YeWsbaIUvo/s4LrExn5url55QtVIIt+2XPBN63S18cgaRcdwRXBWOYLwtJA5W+3v
TOedyJoVhkbOidnCDYA5fMSrad5ZNQ1czVJJABEBAAG0PHBhc3Nib2x0IGFkbWlu
IChLZXkgZm9yIHBhc3Nib2x0IGFkbWluKSA8YWRtaW5AcGFzc2JvbHQuY29tPokB
NwQTAQoAIQUCVQwP/QIbAwULCQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRB+3OWe
xaCd0vAiB/4wo8yleshp9Y/QVosrJxUpMYxomuxep7N/ayX5KuA7n8q9/PLy9cJW
+7r+SyDBgaVPgDGGViDZTJJExzi9o2+zYqjCIwrChCZCmumpTJTALMD7u2Z7LoWA
JC57QviZA8CMviLPrk3/iNHxPpgXe9D3pbGaBi797BF08hy4ivvgLfCs51gAlRe6
TIiLkTJIDwYfO50YXZ7RJ4NLsFNroukRnq9FA+67WbBNiOsAxf36cpxKUoKq82bd
R/fkvZ0uudcHlATPTeW4qYCUk8LUN3i5YjxfHUd+vvM0du91PmADuXFmRfyk3KGl
TEDzFOGbADmP1IWqjsvsgyTTNzbavnU3uQENBFUMD/0BCADtx0yQt20C4KCMCXbB
yWezcS11CVRTRK8BktztbVg5Ga6DJD5SNhmcBgKXttSgsn4d+TKKRG/fSur7/zpC
0cFumHt5nfSyuTfv5IsFbZrIEZeaNGWfA7XA7eFxeCEnvyIWRW4mOT/0tb9BJ1cv
I0FWOsSfoG1YSxs50Ry4TjiEH5TeOrPK70DMHUvtq7lrddcWEgAGnzwV3b4te9a7
Jfn7di9py5IFeF/4JmawK+tKA3r1bI5MyRPdu+TzqrJHwemrelPyQ4NtDFH0L03y
vP6YMkfKioW1I7pll2LH7/WI9wOdKqXfTUGc6ClhJyXLPi39FwcLsoj3ujTCD7ic
TzjJABEBAAGJAR8EGAEKAAkFAlUMD/0CGwwACgkQftzlnsWgndLprAf9HLmTmjTP
UtCAhBKWRJ/Piadijhn0wSyoBBLs1LqnRlTMacFYy1gLR+URx0fScnwkBuxm9n1E
5a6LLnEgAT4HqdP0HXzenFs5XqidJ3O+pu6iaHGQijOSfCfoSvlMakiEsuWDxwOK
AjMlHecW38rhO4XOsl/oHvGH0/BwM8HnX9AVx5lw6W0FWRiwXSoS5zVwtGlCc6r4
UqTiAd5Q0vuzA9P+79bvEFx2WaFIEhGVGTm9oA8YifiYPKiYhE0eQYDJn0XjUE0D
/J5G4YM/zr02sRGaceWR4PIitF8ff8iSW2bvqqu4UIlocAfTFw+Lu9v18koU8Z12
bZVOcH7NTo3GAg==
=ZMGQ
-----END PGP PUBLIC KEY BLOCK-----
',
			'bits' => '2046',
			'uid' => 'passbolt admin (Key for passbolt admin) <admin@passbolt.com>',
			'key_id' => 'C5A09DD2',
			'fingerprint' => 'E60BF449D034310A579F508E7EDCE59EC5A09DD2',
			'type' => 'RSA',
			'expires' => null,
			'key_created' => '2015-03-20 13:18:05',
			'deleted' => 0,
			'created' => '2015-08-17 13:46:29',
			'modified' => '2015-08-17 13:46:29',
			'created_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'modified_by' => '533d37a0-bc80-4945-9b11-1663c0a895dc'
		),
	);

}
