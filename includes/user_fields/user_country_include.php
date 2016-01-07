<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_country_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
   echo "<tr>\n";
   echo "<td class='tbl'>".$locale['uf_country'].":</td>\n";
   echo "<td class='tbl'>\n";
   
   echo "<select name='user_country' class='textbox' onchange='show_flag();'>\n";

   echo "<option value='ZZ'>---</option>\n";

   echo "<option value='AF'".($userdata['user_country'] == "AF" ? " selected" : "").">".$locale['uf_country_af']."</option>\n";
   echo "<option value='AL'".($userdata['user_country'] == "AL" ? " selected" : "").">".$locale['uf_country_al']."</option>\n";
   echo "<option value='DZ'".($userdata['user_country'] == "DZ" ? " selected" : "").">".$locale['uf_country_dz']."</option>\n";
   echo "<option value='AS'".($userdata['user_country'] == "AS" ? " selected" : "").">".$locale['uf_country_as']."</option>\n";
   echo "<option value='AD'".($userdata['user_country'] == "AD" ? " selected" : "").">".$locale['uf_country_ad']."</option>\n";
   echo "<option value='AO'".($userdata['user_country'] == "AO" ? " selected" : "").">".$locale['uf_country_ao']."</option>\n";
   echo "<option value='AI'".($userdata['user_country'] == "AI" ? " selected" : "").">".$locale['uf_country_ai']."</option>\n";
   echo "<option value='AQ'".($userdata['user_country'] == "AQ" ? " selected" : "").">".$locale['uf_country_aq']."</option>\n";
   echo "<option value='AG'".($userdata['user_country'] == "AG" ? " selected" : "").">".$locale['uf_country_ag']."</option>\n";
   echo "<option value='AR'".($userdata['user_country'] == "AR" ? " selected" : "").">".$locale['uf_country_ar']."</option>\n";
   echo "<option value='AM'".($userdata['user_country'] == "AM" ? " selected" : "").">".$locale['uf_country_am']."</option>\n";
   echo "<option value='AW'".($userdata['user_country'] == "AW" ? " selected" : "").">".$locale['uf_country_aw']."</option>\n";
   echo "<option value='AU'".($userdata['user_country'] == "AU" ? " selected" : "").">".$locale['uf_country_au']."</option>\n";
   echo "<option value='AT'".($userdata['user_country'] == "AT" ? " selected" : "").">".$locale['uf_country_at']."</option>\n";
   echo "<option value='AZ'".($userdata['user_country'] == "AZ" ? " selected" : "").">".$locale['uf_country_az']."</option>\n";
   echo "<option value='BS'".($userdata['user_country'] == "BS" ? " selected" : "").">".$locale['uf_country_bs']."</option>\n";
   echo "<option value='BH'".($userdata['user_country'] == "BH" ? " selected" : "").">".$locale['uf_country_bh']."</option>\n";
   echo "<option value='BD'".($userdata['user_country'] == "BD" ? " selected" : "").">".$locale['uf_country_bd']."</option>\n";
   echo "<option value='BB'".($userdata['user_country'] == "BB" ? " selected" : "").">".$locale['uf_country_bb']."</option>\n";
   echo "<option value='BY'".($userdata['user_country'] == "BY" ? " selected" : "").">".$locale['uf_country_by']."</option>\n";
   echo "<option value='BE'".($userdata['user_country'] == "BE" ? " selected" : "").">".$locale['uf_country_be']."</option>\n";
   echo "<option value='BZ'".($userdata['user_country'] == "BZ" ? " selected" : "").">".$locale['uf_country_bz']."</option>\n";
   echo "<option value='BJ'".($userdata['user_country'] == "BJ" ? " selected" : "").">".$locale['uf_country_bj']."</option>\n";
   echo "<option value='BM'".($userdata['user_country'] == "BM" ? " selected" : "").">".$locale['uf_country_bm']."</option>\n";
   echo "<option value='BT'".($userdata['user_country'] == "BT" ? " selected" : "").">".$locale['uf_country_bt']."</option>\n";
   echo "<option value='BO'".($userdata['user_country'] == "BO" ? " selected" : "").">".$locale['uf_country_bo']."</option>\n";
   echo "<option value='BA'".($userdata['user_country'] == "BA" ? " selected" : "").">".$locale['uf_country_ba']."</option>\n";
   echo "<option value='BW'".($userdata['user_country'] == "BW" ? " selected" : "").">".$locale['uf_country_bw']."</option>\n";
   echo "<option value='BV'".($userdata['user_country'] == "BV" ? " selected" : "").">".$locale['uf_country_bv']."</option>\n";
   echo "<option value='BR'".($userdata['user_country'] == "BR" ? " selected" : "").">".$locale['uf_country_br']."</option>\n";
   echo "<option value='IO'".($userdata['user_country'] == "IO" ? " selected" : "").">".$locale['uf_country_io']."</option>\n";
   echo "<option value='BN'".($userdata['user_country'] == "BN" ? " selected" : "").">".$locale['uf_country_bn']."</option>\n";
   echo "<option value='BG'".($userdata['user_country'] == "BG" ? " selected" : "").">".$locale['uf_country_bg']."</option>\n";
   echo "<option value='BF'".($userdata['user_country'] == "BF" ? " selected" : "").">".$locale['uf_country_bf']."</option>\n";
   echo "<option value='BI'".($userdata['user_country'] == "BI" ? " selected" : "").">".$locale['uf_country_bi']."</option>\n";
   echo "<option value='KH'".($userdata['user_country'] == "KH" ? " selected" : "").">".$locale['uf_country_kh']."</option>\n";
   echo "<option value='CM'".($userdata['user_country'] == "CM" ? " selected" : "").">".$locale['uf_country_cm']."</option>\n";
   echo "<option value='CA'".($userdata['user_country'] == "CA" ? " selected" : "").">".$locale['uf_country_ca']."</option>\n";
   echo "<option value='CV'".($userdata['user_country'] == "CV" ? " selected" : "").">".$locale['uf_country_cv']."</option>\n";
   echo "<option value='KY'".($userdata['user_country'] == "KY" ? " selected" : "").">".$locale['uf_country_ky']."</option>\n";
   echo "<option value='CF'".($userdata['user_country'] == "CF" ? " selected" : "").">".$locale['uf_country_cf']."</option>\n";
   echo "<option value='TD'".($userdata['user_country'] == "TD" ? " selected" : "").">".$locale['uf_country_td']."</option>\n";
   echo "<option value='CD'".($userdata['user_country'] == "CD" ? " selected" : "").">".$locale['uf_country_cd']."</option>\n";
   echo "<option value='CL'".($userdata['user_country'] == "CL" ? " selected" : "").">".$locale['uf_country_cl']."</option>\n";
   echo "<option value='CN'".($userdata['user_country'] == "CN" ? " selected" : "").">".$locale['uf_country_cn']."</option>\n";
   echo "<option value='CX'".($userdata['user_country'] == "CX" ? " selected" : "").">".$locale['uf_country_cx']."</option>\n";
   echo "<option value='CS'".($userdata['user_country'] == "CS" ? " selected" : "").">".$locale['uf_country_cs']."</option>\n";
   echo "<option value='CO'".($userdata['user_country'] == "CO" ? " selected" : "").">".$locale['uf_country_co']."</option>\n";
   echo "<option value='CC'".($userdata['user_country'] == "CC" ? " selected" : "").">".$locale['uf_country_cc']."</option>\n";
   echo "<option value='KM'".($userdata['user_country'] == "KM" ? " selected" : "").">".$locale['uf_country_km']."</option>\n";
   echo "<option value='CG'".($userdata['user_country'] == "CG" ? " selected" : "").">".$locale['uf_country_cg']."</option>\n";
   echo "<option value='CK'".($userdata['user_country'] == "CK" ? " selected" : "").">".$locale['uf_country_ck']."</option>\n";
   echo "<option value='CR'".($userdata['user_country'] == "CR" ? " selected" : "").">".$locale['uf_country_cr']."</option>\n";
   echo "<option value='CI'".($userdata['user_country'] == "CI" ? " selected" : "").">".$locale['uf_country_ci']."</option>\n";
   echo "<option value='HR'".($userdata['user_country'] == "HR" ? " selected" : "").">".$locale['uf_country_hr']."</option>\n";
   echo "<option value='CU'".($userdata['user_country'] == "CU" ? " selected" : "").">".$locale['uf_country_cu']."</option>\n";
   echo "<option value='CB'".($userdata['user_country'] == "CB" ? " selected" : "").">".$locale['uf_country_cb']."</option>\n";
   echo "<option value='CY'".($userdata['user_country'] == "CY" ? " selected" : "").">".$locale['uf_country_cy']."</option>\n";
   echo "<option value='CZ'".($userdata['user_country'] == "CZ" ? " selected" : "").">".$locale['uf_country_cz']."</option>\n";
   echo "<option value='DK'".($userdata['user_country'] == "DK" ? " selected" : "").">".$locale['uf_country_dk']."</option>\n";
   echo "<option value='DJ'".($userdata['user_country'] == "DJ" ? " selected" : "").">".$locale['uf_country_dj']."</option>\n";
   echo "<option value='DM'".($userdata['user_country'] == "DM" ? " selected" : "").">".$locale['uf_country_dm']."</option>\n";
   echo "<option value='DO'".($userdata['user_country'] == "DO" ? " selected" : "").">".$locale['uf_country_do']."</option>\n";
   echo "<option value='TM'".($userdata['user_country'] == "TM" ? " selected" : "").">".$locale['uf_country_tm']."</option>\n";
   echo "<option value='EC'".($userdata['user_country'] == "EC" ? " selected" : "").">".$locale['uf_country_ec']."</option>\n";
   echo "<option value='EG'".($userdata['user_country'] == "EG" ? " selected" : "").">".$locale['uf_country_eg']."</option>\n";
   echo "<option value='SV'".($userdata['user_country'] == "SV" ? " selected" : "").">".$locale['uf_country_sv']."</option>\n";
   echo "<option value='GQ'".($userdata['user_country'] == "GQ" ? " selected" : "").">".$locale['uf_country_gq']."</option>\n";
   echo "<option value='ER'".($userdata['user_country'] == "ER" ? " selected" : "").">".$locale['uf_country_er']."</option>\n";
   echo "<option value='EE'".($userdata['user_country'] == "EE" ? " selected" : "").">".$locale['uf_country_ee']."</option>\n";
   echo "<option value='ET'".($userdata['user_country'] == "ET" ? " selected" : "").">".$locale['uf_country_et']."</option>\n";
   echo "<option value='FK'".($userdata['user_country'] == "FK" ? " selected" : "").">".$locale['uf_country_fk']."</option>\n";
   echo "<option value='FO'".($userdata['user_country'] == "FO" ? " selected" : "").">".$locale['uf_country_fo']."</option>\n";
   echo "<option value='FJ'".($userdata['user_country'] == "FJ" ? " selected" : "").">".$locale['uf_country_fj']."</option>\n";
   echo "<option value='FI'".($userdata['user_country'] == "FI" ? " selected" : "").">".$locale['uf_country_fi']."</option>\n";
   echo "<option value='FR'".($userdata['user_country'] == "FR" ? " selected" : "").">".$locale['uf_country_fr']."</option>\n";
   echo "<option value='GF'".($userdata['user_country'] == "GF" ? " selected" : "").">".$locale['uf_country_gf']."</option>\n";
   echo "<option value='PF'".($userdata['user_country'] == "PF" ? " selected" : "").">".$locale['uf_country_pf']."</option>\n";
   echo "<option value='TF'".($userdata['user_country'] == "TF" ? " selected" : "").">".$locale['uf_country_tf']."</option>\n";
   echo "<option value='GA'".($userdata['user_country'] == "GA" ? " selected" : "").">".$locale['uf_country_ga']."</option>\n";
   echo "<option value='GM'".($userdata['user_country'] == "GM" ? " selected" : "").">".$locale['uf_country_gm']."</option>\n";
   echo "<option value='GE'".($userdata['user_country'] == "GE" ? " selected" : "").">".$locale['uf_country_ge']."</option>\n";
   echo "<option value='DE'".($userdata['user_country'] == "DE" ? " selected" : "").">".$locale['uf_country_de']."</option>\n";
   echo "<option value='GH'".($userdata['user_country'] == "GH" ? " selected" : "").">".$locale['uf_country_gh']."</option>\n";
   echo "<option value='GI'".($userdata['user_country'] == "GI" ? " selected" : "").">".$locale['uf_country_gi']."</option>\n";
   echo "<option value='GR'".($userdata['user_country'] == "GR" ? " selected" : "").">".$locale['uf_country_gr']."</option>\n";
   echo "<option value='GL'".($userdata['user_country'] == "GL" ? " selected" : "").">".$locale['uf_country_gl']."</option>\n";
   echo "<option value='GD'".($userdata['user_country'] == "GD" ? " selected" : "").">".$locale['uf_country_gd']."</option>\n";
   echo "<option value='GP'".($userdata['user_country'] == "GP" ? " selected" : "").">".$locale['uf_country_gp']."</option>\n";
   echo "<option value='GU'".($userdata['user_country'] == "GU" ? " selected" : "").">".$locale['uf_country_gu']."</option>\n";
   echo "<option value='GT'".($userdata['user_country'] == "GT" ? " selected" : "").">".$locale['uf_country_gt']."</option>\n";
   echo "<option value='GN'".($userdata['user_country'] == "GN" ? " selected" : "").">".$locale['uf_country_gn']."</option>\n";
   echo "<option value='GW'".($userdata['user_country'] == "GW" ? " selected" : "").">".$locale['uf_country_gw']."</option>\n";
   echo "<option value='GY'".($userdata['user_country'] == "GY" ? " selected" : "").">".$locale['uf_country_gy']."</option>\n";
   echo "<option value='HT'".($userdata['user_country'] == "HT" ? " selected" : "").">".$locale['uf_country_ht']."</option>\n";
   echo "<option value='HM'".($userdata['user_country'] == "HM" ? " selected" : "").">".$locale['uf_country_hm']."</option>\n";
   echo "<option value='HN'".($userdata['user_country'] == "HN" ? " selected" : "").">".$locale['uf_country_hn']."</option>\n";
   echo "<option value='HK'".($userdata['user_country'] == "HK" ? " selected" : "").">".$locale['uf_country_hk']."</option>\n";
   echo "<option value='HU'".($userdata['user_country'] == "HU" ? " selected" : "").">".$locale['uf_country_hu']."</option>\n";
   echo "<option value='IS'".($userdata['user_country'] == "IS" ? " selected" : "").">".$locale['uf_country_is']."</option>\n";
   echo "<option value='IN'".($userdata['user_country'] == "IN" ? " selected" : "").">".$locale['uf_country_in']."</option>\n";
   echo "<option value='ID'".($userdata['user_country'] == "ID" ? " selected" : "").">".$locale['uf_country_id']."</option>\n";
   echo "<option value='IR'".($userdata['user_country'] == "IR" ? " selected" : "").">".$locale['uf_country_ir']."</option>\n";
   echo "<option value='IQ'".($userdata['user_country'] == "IQ" ? " selected" : "").">".$locale['uf_country_iq']."</option>\n";
   echo "<option value='IE'".($userdata['user_country'] == "IE" ? " selected" : "").">".$locale['uf_country_ie']."</option>\n";
   echo "<option value='IL'".($userdata['user_country'] == "IL" ? " selected" : "").">".$locale['uf_country_il']."</option>\n";
   echo "<option value='IT'".($userdata['user_country'] == "IT" ? " selected" : "").">".$locale['uf_country_it']."</option>\n";
   echo "<option value='JM'".($userdata['user_country'] == "JM" ? " selected" : "").">".$locale['uf_country_jm']."</option>\n";
   echo "<option value='JP'".($userdata['user_country'] == "JP" ? " selected" : "").">".$locale['uf_country_jp']."</option>\n";
   echo "<option value='JO'".($userdata['user_country'] == "JO" ? " selected" : "").">".$locale['uf_country_jo']."</option>\n";
   echo "<option value='KZ'".($userdata['user_country'] == "KZ" ? " selected" : "").">".$locale['uf_country_kz']."</option>\n";
   echo "<option value='KE'".($userdata['user_country'] == "KE" ? " selected" : "").">".$locale['uf_country_ke']."</option>\n";
   echo "<option value='KI'".($userdata['user_country'] == "KI" ? " selected" : "").">".$locale['uf_country_ki']."</option>\n";
   echo "<option value='KP'".($userdata['user_country'] == "KP" ? " selected" : "").">".$locale['uf_country_kp']."</option>\n";
   echo "<option value='KR'".($userdata['user_country'] == "KR" ? " selected" : "").">".$locale['uf_country_kr']."</option>\n";
   echo "<option value='KW'".($userdata['user_country'] == "KW" ? " selected" : "").">".$locale['uf_country_kw']."</option>\n";
   echo "<option value='KG'".($userdata['user_country'] == "KG" ? " selected" : "").">".$locale['uf_country_kg']."</option>\n";
   echo "<option value='LA'".($userdata['user_country'] == "LA" ? " selected" : "").">".$locale['uf_country_la']."</option>\n";
   echo "<option value='LV'".($userdata['user_country'] == "LV" ? " selected" : "").">".$locale['uf_country_lv']."</option>\n";
   echo "<option value='LB'".($userdata['user_country'] == "LB" ? " selected" : "").">".$locale['uf_country_lb']."</option>\n";
   echo "<option value='LS'".($userdata['user_country'] == "LS" ? " selected" : "").">".$locale['uf_country_ls']."</option>\n";
   echo "<option value='LR'".($userdata['user_country'] == "LR" ? " selected" : "").">".$locale['uf_country_lr']."</option>\n";
   echo "<option value='LY'".($userdata['user_country'] == "LY" ? " selected" : "").">".$locale['uf_country_ly']."</option>\n";
   echo "<option value='LI'".($userdata['user_country'] == "LI" ? " selected" : "").">".$locale['uf_country_li']."</option>\n";
   echo "<option value='LT'".($userdata['user_country'] == "LT" ? " selected" : "").">".$locale['uf_country_lt']."</option>\n";
   echo "<option value='LU'".($userdata['user_country'] == "LU" ? " selected" : "").">".$locale['uf_country_lu']."</option>\n";
   echo "<option value='MO'".($userdata['user_country'] == "MO" ? " selected" : "").">".$locale['uf_country_mo']."</option>\n";
   echo "<option value='MK'".($userdata['user_country'] == "MK" ? " selected" : "").">".$locale['uf_country_mk']."</option>\n";
   echo "<option value='MG'".($userdata['user_country'] == "MG" ? " selected" : "").">".$locale['uf_country_mg']."</option>\n";
   echo "<option value='MY'".($userdata['user_country'] == "MY" ? " selected" : "").">".$locale['uf_country_my']."</option>\n";
   echo "<option value='MW'".($userdata['user_country'] == "MW" ? " selected" : "").">".$locale['uf_country_mw']."</option>\n";
   echo "<option value='MV'".($userdata['user_country'] == "MV" ? " selected" : "").">".$locale['uf_country_mv']."</option>\n";
   echo "<option value='ML'".($userdata['user_country'] == "ML" ? " selected" : "").">".$locale['uf_country_ml']."</option>\n";
   echo "<option value='MT'".($userdata['user_country'] == "MT" ? " selected" : "").">".$locale['uf_country_mt']."</option>\n";
   echo "<option value='MH'".($userdata['user_country'] == "MH" ? " selected" : "").">".$locale['uf_country_mh']."</option>\n";
   echo "<option value='MQ'".($userdata['user_country'] == "MQ" ? " selected" : "").">".$locale['uf_country_mq']."</option>\n";
   echo "<option value='MR'".($userdata['user_country'] == "MR" ? " selected" : "").">".$locale['uf_country_mr']."</option>\n";
   echo "<option value='MU'".($userdata['user_country'] == "MU" ? " selected" : "").">".$locale['uf_country_mu']."</option>\n";
   echo "<option value='YT'".($userdata['user_country'] == "YT" ? " selected" : "").">".$locale['uf_country_yt']."</option>\n";
   echo "<option value='MX'".($userdata['user_country'] == "MX" ? " selected" : "").">".$locale['uf_country_mx']."</option>\n";
   echo "<option value='FM'".($userdata['user_country'] == "FM" ? " selected" : "").">".$locale['uf_country_fm']."</option>\n";
   echo "<option value='MD'".($userdata['user_country'] == "MD" ? " selected" : "").">".$locale['uf_country_md']."</option>\n";
   echo "<option value='MC'".($userdata['user_country'] == "MC" ? " selected" : "").">".$locale['uf_country_mc']."</option>\n";
   echo "<option value='MN'".($userdata['user_country'] == "MN" ? " selected" : "").">".$locale['uf_country_mn']."</option>\n";
   echo "<option value='ME'".($userdata['user_country'] == "ME" ? " selected" : "").">".$locale['uf_country_me']."</option>\n";
   echo "<option value='MS'".($userdata['user_country'] == "MS" ? " selected" : "").">".$locale['uf_country_ms']."</option>\n";
   echo "<option value='MA'".($userdata['user_country'] == "MA" ? " selected" : "").">".$locale['uf_country_ma']."</option>\n";
   echo "<option value='MZ'".($userdata['user_country'] == "MZ" ? " selected" : "").">".$locale['uf_country_mz']."</option>\n";
   echo "<option value='MM'".($userdata['user_country'] == "MM" ? " selected" : "").">".$locale['uf_country_mm']."</option>\n";
   echo "<option value='NA'".($userdata['user_country'] == "NA" ? " selected" : "").">".$locale['uf_country_na']."</option>\n";
   echo "<option value='NR'".($userdata['user_country'] == "NR" ? " selected" : "").">".$locale['uf_country_nr']."</option>\n";
   echo "<option value='NP'".($userdata['user_country'] == "NP" ? " selected" : "").">".$locale['uf_country_np']."</option>\n";
   echo "<option value='AN'".($userdata['user_country'] == "AN" ? " selected" : "").">".$locale['uf_country_an']."</option>\n";
   echo "<option value='NL'".($userdata['user_country'] == "NL" ? " selected" : "").">".$locale['uf_country_nl']."</option>\n";
   echo "<option value='NC'".($userdata['user_country'] == "NC" ? " selected" : "").">".$locale['uf_country_nc']."</option>\n";
   echo "<option value='NZ'".($userdata['user_country'] == "NZ" ? " selected" : "").">".$locale['uf_country_nz']."</option>\n";
   echo "<option value='NI'".($userdata['user_country'] == "NI" ? " selected" : "").">".$locale['uf_country_ni']."</option>\n";
   echo "<option value='NE'".($userdata['user_country'] == "NE" ? " selected" : "").">".$locale['uf_country_ne']."</option>\n";
   echo "<option value='NG'".($userdata['user_country'] == "NG" ? " selected" : "").">".$locale['uf_country_ng']."</option>\n";
   echo "<option value='NU'".($userdata['user_country'] == "NU" ? " selected" : "").">".$locale['uf_country_nu']."</option>\n";
   echo "<option value='NF'".($userdata['user_country'] == "NF" ? " selected" : "").">".$locale['uf_country_nf']."</option>\n";
   echo "<option value='NO'".($userdata['user_country'] == "NO" ? " selected" : "").">".$locale['uf_country_no']."</option>\n";
   echo "<option value='MP'".($userdata['user_country'] == "MP" ? " selected" : "").">".$locale['uf_country_mp']."</option>\n";
   echo "<option value='OM'".($userdata['user_country'] == "OM" ? " selected" : "").">".$locale['uf_country_om']."</option>\n";
   echo "<option value='PK'".($userdata['user_country'] == "PK" ? " selected" : "").">".$locale['uf_country_pk']."</option>\n";
   echo "<option value='PW'".($userdata['user_country'] == "PW" ? " selected" : "").">".$locale['uf_country_pw']."</option>\n";
   echo "<option value='PS'".($userdata['user_country'] == "PS" ? " selected" : "").">".$locale['uf_country_ps']."</option>\n";
   echo "<option value='PA'".($userdata['user_country'] == "PA" ? " selected" : "").">".$locale['uf_country_pa']."</option>\n";
   echo "<option value='PG'".($userdata['user_country'] == "PG" ? " selected" : "").">".$locale['uf_country_pg']."</option>\n";
   echo "<option value='PY'".($userdata['user_country'] == "PY" ? " selected" : "").">".$locale['uf_country_py']."</option>\n";
   echo "<option value='PE'".($userdata['user_country'] == "PE" ? " selected" : "").">".$locale['uf_country_pe']."</option>\n";
   echo "<option value='PH'".($userdata['user_country'] == "PH" ? " selected" : "").">".$locale['uf_country_ph']."</option>\n";
   echo "<option value='PN'".($userdata['user_country'] == "PN" ? " selected" : "").">".$locale['uf_country_pn']."</option>\n";
   echo "<option value='PL'".($userdata['user_country'] == "PL" ? " selected" : "").">".$locale['uf_country_pl']."</option>\n";
   echo "<option value='PT'".($userdata['user_country'] == "PT" ? " selected" : "").">".$locale['uf_country_pt']."</option>\n";
   echo "<option value='PR'".($userdata['user_country'] == "PR" ? " selected" : "").">".$locale['uf_country_pr']."</option>\n";
   echo "<option value='QA'".($userdata['user_country'] == "QA" ? " selected" : "").">".$locale['uf_country_qa']."</option>\n";
   echo "<option value='RE'".($userdata['user_country'] == "RE" ? " selected" : "").">".$locale['uf_country_re']."</option>\n";
   echo "<option value='RO'".($userdata['user_country'] == "RO" ? " selected" : "").">".$locale['uf_country_ro']."</option>\n";
   echo "<option value='RU'".($userdata['user_country'] == "RU" ? " selected" : "").">".$locale['uf_country_ru']."</option>\n";
   echo "<option value='RW'".($userdata['user_country'] == "RW" ? " selected" : "").">".$locale['uf_country_rw']."</option>\n";
   echo "<option value='SH'".($userdata['user_country'] == "SH" ? " selected" : "").">".$locale['uf_country_sh']."</option>\n";
   echo "<option value='KN'".($userdata['user_country'] == "KN" ? " selected" : "").">".$locale['uf_country_kn']."</option>\n";
   echo "<option value='LC'".($userdata['user_country'] == "LC" ? " selected" : "").">".$locale['uf_country_lc']."</option>\n";
   echo "<option value='PM'".($userdata['user_country'] == "PM" ? " selected" : "").">".$locale['uf_country_pm']."</option>\n";
   echo "<option value='VC'".($userdata['user_country'] == "VC" ? " selected" : "").">".$locale['uf_country_vc']."</option>\n";
   echo "<option value='WS'".($userdata['user_country'] == "WS" ? " selected" : "").">".$locale['uf_country_ws']."</option>\n";
   echo "<option value='SM'".($userdata['user_country'] == "SM" ? " selected" : "").">".$locale['uf_country_sm']."</option>\n";
   echo "<option value='ST'".($userdata['user_country'] == "ST" ? " selected" : "").">".$locale['uf_country_st']."</option>\n";
   echo "<option value='SA'".($userdata['user_country'] == "SA" ? " selected" : "").">".$locale['uf_country_sa']."</option>\n";
   echo "<option value='SN'".($userdata['user_country'] == "SN" ? " selected" : "").">".$locale['uf_country_sn']."</option>\n";
   echo "<option value='SC'".($userdata['user_country'] == "SC" ? " selected" : "").">".$locale['uf_country_sc']."</option>\n";
   echo "<option value='XS'".($userdata['user_country'] == "XS" ? " selected" : "").">".$locale['uf_country_xs']."</option>\n";
   echo "<option value='SL'".($userdata['user_country'] == "SL" ? " selected" : "").">".$locale['uf_country_sl']."</option>\n";
   echo "<option value='SG'".($userdata['user_country'] == "SG" ? " selected" : "").">".$locale['uf_country_sg']."</option>\n";
   echo "<option value='SK'".($userdata['user_country'] == "SK" ? " selected" : "").">".$locale['uf_country_sk']."</option>\n";
   echo "<option value='SI'".($userdata['user_country'] == "SI" ? " selected" : "").">".$locale['uf_country_si']."</option>\n";
   echo "<option value='SB'".($userdata['user_country'] == "SB" ? " selected" : "").">".$locale['uf_country_sb']."</option>\n";
   echo "<option value='OI'".($userdata['user_country'] == "OI" ? " selected" : "").">".$locale['uf_country_oi']."</option>\n";
   echo "<option value='ZA'".($userdata['user_country'] == "ZA" ? " selected" : "").">".$locale['uf_country_za']."</option>\n";
   echo "<option value='GS'".($userdata['user_country'] == "GS" ? " selected" : "").">".$locale['uf_country_gs']."</option>\n";
   echo "<option value='ES'".($userdata['user_country'] == "ES" ? " selected" : "").">".$locale['uf_country_es']."</option>\n";
   echo "<option value='LK'".($userdata['user_country'] == "LK" ? " selected" : "").">".$locale['uf_country_lk']."</option>\n";
   echo "<option value='SD'".($userdata['user_country'] == "SD" ? " selected" : "").">".$locale['uf_country_sd']."</option>\n";
   echo "<option value='SR'".($userdata['user_country'] == "SR" ? " selected" : "").">".$locale['uf_country_sr']."</option>\n";
   echo "<option value='SJ'".($userdata['user_country'] == "SJ" ? " selected" : "").">".$locale['uf_country_sj']."</option>\n";
   echo "<option value='SZ'".($userdata['user_country'] == "SZ" ? " selected" : "").">".$locale['uf_country_sz']."</option>\n";
   echo "<option value='SE'".($userdata['user_country'] == "SE" ? " selected" : "").">".$locale['uf_country_se']."</option>\n";
   echo "<option value='CH'".($userdata['user_country'] == "CH" ? " selected" : "").">".$locale['uf_country_ch']."</option>\n";
   echo "<option value='SY'".($userdata['user_country'] == "SY" ? " selected" : "").">".$locale['uf_country_sy']."</option>\n";
   echo "<option value='TA'".($userdata['user_country'] == "TA" ? " selected" : "").">".$locale['uf_country_ta']."</option>\n";
   echo "<option value='TW'".($userdata['user_country'] == "TW" ? " selected" : "").">".$locale['uf_country_tw']."</option>\n";
   echo "<option value='TJ'".($userdata['user_country'] == "TJ" ? " selected" : "").">".$locale['uf_country_tj']."</option>\n";
   echo "<option value='TZ'".($userdata['user_country'] == "TZ" ? " selected" : "").">".$locale['uf_country_tz']."</option>\n";
   echo "<option value='TH'".($userdata['user_country'] == "TH" ? " selected" : "").">".$locale['uf_country_th']."</option>\n";
   echo "<option value='TG'".($userdata['user_country'] == "TG" ? " selected" : "").">".$locale['uf_country_tg']."</option>\n";
   echo "<option value='TK'".($userdata['user_country'] == "TK" ? " selected" : "").">".$locale['uf_country_tk']."</option>\n";
   echo "<option value='TO'".($userdata['user_country'] == "TO" ? " selected" : "").">".$locale['uf_country_to']."</option>\n";
   echo "<option value='TT'".($userdata['user_country'] == "TT" ? " selected" : "").">".$locale['uf_country_tt']."</option>\n";
   echo "<option value='TN'".($userdata['user_country'] == "TN" ? " selected" : "").">".$locale['uf_country_tn']."</option>\n";
   echo "<option value='TR'".($userdata['user_country'] == "TR" ? " selected" : "").">".$locale['uf_country_tr']."</option>\n";
   echo "<option value='TM'".($userdata['user_country'] == "TM" ? " selected" : "").">".$locale['uf_country_tm']."</option>\n";
   echo "<option value='TC'".($userdata['user_country'] == "TC" ? " selected" : "").">".$locale['uf_country_tc']."</option>\n";
   echo "<option value='TV'".($userdata['user_country'] == "TV" ? " selected" : "").">".$locale['uf_country_tv']."</option>\n";
   echo "<option value='UG'".($userdata['user_country'] == "UG" ? " selected" : "").">".$locale['uf_country_ug']."</option>\n";
   echo "<option value='UA'".($userdata['user_country'] == "UA" ? " selected" : "").">".$locale['uf_country_ua']."</option>\n";
   echo "<option value='AE'".($userdata['user_country'] == "AE" ? " selected" : "").">".$locale['uf_country_ae']."</option>\n";
   echo "<option value='GB'".($userdata['user_country'] == "GB" ? " selected" : "").">".$locale['uf_country_gb']."</option>\n";
   echo "<option value='UM'".($userdata['user_country'] == "UM" ? " selected" : "").">".$locale['uf_country_um']."</option>\n";
   echo "<option value='US'".($userdata['user_country'] == "US" ? " selected" : "").">".$locale['uf_country_us']."</option>\n";
   echo "<option value='UY'".($userdata['user_country'] == "UY" ? " selected" : "").">".$locale['uf_country_uy']."</option>\n";
   echo "<option value='UZ'".($userdata['user_country'] == "UZ" ? " selected" : "").">".$locale['uf_country_uz']."</option>\n";
   echo "<option value='VU'".($userdata['user_country'] == "VU" ? " selected" : "").">".$locale['uf_country_vu']."</option>\n";
   echo "<option value='VA'".($userdata['user_country'] == "VA" ? " selected" : "").">".$locale['uf_country_va']."</option>\n";
   echo "<option value='VE'".($userdata['user_country'] == "VE" ? " selected" : "").">".$locale['uf_country_ve']."</option>\n";
   echo "<option value='VN'".($userdata['user_country'] == "VN" ? " selected" : "").">".$locale['uf_country_vn']."</option>\n";
   echo "<option value='VG'".($userdata['user_country'] == "VG" ? " selected" : "").">".$locale['uf_country_vg']."</option>\n";
   echo "<option value='VI'".($userdata['user_country'] == "VI" ? " selected" : "").">".$locale['uf_country_vi']."</option>\n";
   echo "<option value='WF'".($userdata['user_country'] == "WF" ? " selected" : "").">".$locale['uf_country_wf']."</option>\n";
   echo "<option value='EH'".($userdata['user_country'] == "EH" ? " selected" : "").">".$locale['uf_country_eh']."</option>\n";
   echo "<option value='YE'".($userdata['user_country'] == "YE" ? " selected" : "").">".$locale['uf_country_ye']."</option>\n";
   echo "<option value='YU'".($userdata['user_country'] == "YU" ? " selected" : "").">".$locale['uf_country_yu']."</option>\n";
   echo "<option value='ZR'".($userdata['user_country'] == "ZR" ? " selected" : "").">".$locale['uf_country_zr']."</option>\n";
   echo "<option value='ZM'".($userdata['user_country'] == "ZM" ? " selected" : "").">".$locale['uf_country_zm']."</option>\n";
   echo "<option value='ZW'".($userdata['user_country'] == "ZW" ? " selected" : "").">".$locale['uf_country_zw']."</option>\n";
   
   echo "</select>\n";
   
   $flag = strtolower($userdata['user_country']); $flag = $flag ? $flag : "zz";
   echo "<img id='user_country_flag' src='".IMAGES."user_flags/".$flag.".png' alt='".$userdata['user_country']."' title='".$locale['uf_country_'.$flag]."' />";
   
   echo "<script type=\"text/javascript\">
	function show_flag() {
		imgsrc = document.inputform.user_country.value;
		document.images[\"user_country_flag\"].src = \"".IMAGES."user_flags/\" + imgsrc + \".png\";
	}
	</script>";
   
   echo "</td>\n";
   echo "</tr>\n";
   
} elseif ($profile_method == "display") {
   if ($user_data['user_country']) {
   echo "<tr>\n";
   echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_country']."</td>\n";
   echo "<td align='right' class='tbl1'>\n";
   $flag = strtolower($user_data['user_country']);
   echo "<img src='".IMAGES."user_flags/".$flag.".png' alt='".$user_data['user_country']."' title='".$locale['uf_country_'.$flag]."' />\n";
   echo "</td>\n";
   echo "</tr>\n";   
}
} elseif ($profile_method == "validate_insert") {
   $db_fields .= ", user_country";
   $db_values .= ", '".(isset($_POST['user_country']) ? stripinput(trim($_POST['user_country'])) : "")."'";
} elseif ($profile_method == "validate_update") {
   $db_values .= ", user_country='".(isset($_POST['user_country']) ? stripinput(trim($_POST['user_country'])) : "")."'";
}
?>