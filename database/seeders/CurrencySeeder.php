<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
        ["name" => "Afghan afghani","sign" => "؋","code" => "AFN"],
        ["name" => "Euro", "sign" => "€","code" => "EUR", "is_active" => 1],
        ["name" => "Albanian lek", "sign" => "Lek","code" => "ALL"],
        ["name" => "Algerian dinar", "sign" => "دج","code" => "DZD"],
        ["name" => "United States dollar", "sign" => "$","code" => "USD", "is_active" => 1],
        ["name" => "Angolan kwanza", "sign" => "Kz","code" => "AOA"],
        ["name" => "Eastern Caribbean dollar", "sign" => "$","code" => "XCD"],
        ["name" => "Antarctic dollar", "sign" => "$","code" => "AAD"],
        ["name" => "Argentine peso", "sign" => "$","code" => "ARS"],
        ["name" => "Armenian dram", "sign" => "֏","code" => "AMD"],
        ["name" => "Russian ruble", "sign" => "ƒ","code" => "AWG"],
        ["name" => "Australian dollar", "sign" => "$","code" => "AUD"],
        ["name" => "Azerbaijani manat", "sign" => "m","code" => "AZN"],
        ["name" => "Bahamian dollar", "sign" => "B$","code" => "BSD"],
        ["name" => "Bahraini dinar", "sign" => ".د.ب","code" => "BHD"],
        ["name" => "Barbadian dollar", "sign" => "Bds$","code" => "BBD"],
        ["name" => "Belarusian ruble", "sign" => "Br","code" => "BYN"],
        ["name" => "Belize dollar", "sign" => "$","code" => "BZD"],
        ["name" => "West African CFA franc", "sign" => "CFA","code" => "XOF"],
        ["name" => "Bermudian dollar", "sign" => "$","code" => "BMD"],
        ["name" => "Bhutanese ngultrum", "sign" => "Nu.","code" => "BTN"],
        ["name" => "Bolivian boliviano", "sign" => "Bs.","code" => "BOB"],
        ["name" => "Bosnia and Herzegovina convertible mark", "sign" => "KM","code" => "BAM"],
        ["name" => "Botswana pula", "sign" => "P","code" => "BWP"],
        ["name" => "Norwegian krone", "sign" => "kr","code" => "NOK"],
        ["name" => "Brazilian real", "sign" => "R$","code" => "BRL"],
        ["name" => "Brunei dollar", "sign" => "B$","code" => "BND"],
        ["name" => "Bulgarian lev", "sign" => "Лв.","code" => "BGN"],
        ["name" => "Burundian franc	", "sign" => "FBu","code" => "BIF"],
        ["name" => "Cambodian riel", "sign" => "KHR","code" => "KHR"],
        ["name" => "Central African CFA franc", "sign" => "FCFA","code" => "XAF"],
        ["name" => "Canadian dollar", "sign" => "$","code" => "CAD", "is_active" => 1],
        ["name" => "Cape Verdean escudo	", "sign" => "$","code" => "CVE"],
        ["name" => "Cayman Islands dollar", "sign" => "$","code" => "KYD"],
        ["name" => "Chilean peso", "sign" => "$","code" => "CLP"],
        ["name" => "Chinese yuan", "sign" => "¥","code" => "CNY"],
        ["name" => "Colombian peso", "sign" => "$","code" => "COP"],
        ["name" => "Comorian franc", "sign" => "CF","code" => "KMF"],
        ["name" => "Congolese franc", "sign" => "FC","code" => "CDF"],
        ["name" => "Colones", "sign" => "₡","code" => "CRC"],
        ["name" => "Croatian kuna", "sign" => "kn","code" => "HRK"],
        ["name" => "Cuban peso", "sign" => "$","code" => "CUP"],
        ["name" => "Czech koruna", "sign" => "Kč","code" => "CZK"],
        ["name" => "Danish krone", "sign" => "Kr.","code" => "DKK"],
        ["name" => "Djiboutian franc", "sign" => "Fdj","code" => "DJF"],
        ["name" => "Dominican peso", "sign" => "$","code" => "DOP"],
        ["name" => "Egyptian pound", "sign" => "ج.م","code" => "EGP"],
        ["name" => "Eritrean nakfa", "sign" => "Nfk","code" => "ERN"],
        ["name" => "Ethiopian birr", "sign" => "Nkf","code" => "ETB"],
        ["name" => "Falkland Islands pound", "sign" => "£","code" => "FKP"],
        ["name" => "Fijian dollar", "sign" => "FJ$","code" => "FJD"],
        ["name" => "CFP franc", "sign" => "₣","code" => "XPF"],
        ["name" => "Gambian dalasi", "sign" => "D","code" => "GMD"],
        ["name" => "Georgian lari", "sign" => "ლ","code" => "GEL"],
        ["name" => "Ghanaian cedi", "sign" => "GH₵","code" => "GHS", "is_active" => 1],
        ["name" => "Gibraltar pound", "sign" => "£","code" => "GIP"],
        ["name" => "Guatemalan quetzal", "sign" => "Q","code" => "GTQ"],
        ["name" => "British pound", "sign" => "£","code" => "GBP", "is_active" => 1],
        ["name" => "Guinean franc", "sign" => "FG","code" => "GNF"],
        ["name" => "Guyanese dollar", "sign" => "$","code" => "GYD"],
        ["name" => "Haitian gourde", "sign" => "G","code" => "HTG"],
        ["name" => "Honduran lempira", "sign" => "L","code" => "HNL"],
        ["name" => "Hong Kong dollar", "sign" => "$","code" => "HKD"],
        ["name" => "Hungarian forint", "sign" => "Ft","code" => "HUF"],
        ["name" => "Icelandic króna", "sign" => "kr","code" => "ISK"],
        ["name" => "Indian rupee", "sign" => "₹","code" => "INR"],
        ["name" => "Indonesian rupiah", "sign" => "Rp","code" => "IDR"],
        ["name" => "Iranian rial", "sign" => "﷼","code" => "IRR"],
        ["name" => "Iraqi dinar", "sign" => "د.ع","code" => "IQD"],
        ["name" => "Israeli new shekel", "sign" => "₪","code" => "ILS"],
        ["name" => "Jamaican dollar", "sign" => "J$","code" => "JMD"],
        ["name" => "Japanese yen", "sign" => "¥","code" => "JPY"],
        ["name" => "Jordanian dinar", "sign" => "ا.د","code" => "JOD"],
        ["name" => "Kazakhstani tenge", "sign" => "лв","code" => "KZT"],
        ["name" => "Kenyan shilling", "sign" => "KSh","code" => "KES", "is_active" => 1],
        ["name" => "North Korean won", "sign" => "₩","code" => "KPW"],
        ["name" => "South Korean won", "sign" => "₩","code" => "KRW"],
        ["name" => "Kuwaiti dinar", "sign" => "ك.د","code" => "KWD"],
        ["name" => "Kyrgyzstani som", "sign" => "лв","code" => "KGS"],
        ["name" => "Lao kip", "sign" => "₭","code" => "LAK"],
        ["name" => "Lebanese pound", "sign" => "£","code" => "LBP"],
        ["name" => "Lesotho loti", "sign" => "L","code" => "LSL"],
        ["name" => "Liberian dollar", "sign" => "$","code" => "LRD"],
        ["name" => "Libyan dinar", "sign" => "د.ل","code" => "LYD"],
        ["name" => "Swiss franc", "sign" => "CHf","code" => "CHF"],
        ["name" => "Macanese pataca", "sign" => "$","code" => "MOP"],
        ["name" => "Macedonian denar", "sign" => "ден","code" => "MKD"],
        ["name" => "Malagasy ariary", "sign" => "Ar","code" => "MGA"],
        ["name" => "Malawian kwacha", "sign" => "MK","code" => "MWK"],
        ["name" => "Malaysian ringgit", "sign" => "RM","code" => "MYR"],
        ["name" => "Maldivian rufiyaa", "sign" => "Rf","code" => "MVR"],
        ["name" => "Mauritanian ouguiya", "sign" => "MRU","code" => "MRO"],
        ["name" => "Mauritian rupee", "sign" => "₨","code" => "MUR"],
        ["name" => "Mexican peso", "sign" => "$","code" => "MXN"],
        ["name" => "Moldovan leu", "sign" => "L","code" => "MDL"],
        ["name" => "Mongolian tögrög", "sign" => "₮","code" => "MNT"],
        ["name" => "Moroccan dirham", "sign" => "DH","code" => "MAD"],
        ["name" => "Mozambican metical", "sign" => "MT","code" => "MZN"],
        ["name" => "Burmese kyat", "sign" => "K","code" => "MMK"],
        ["name" => "Namibian dollar", "sign" => "$","code" => "NAD"],
        ["name" => "Nepalese rupee", "sign" => "₨","code" => "NPR"],
        ["name" => "New Zealand dollar", "sign" => "$","code" => "NZD"],
        ["name" => "Nicaraguan córdoba", "sign" => "C$","code" => "NIO"],
        ["name" => "Nigerian naira", "sign" => "₦","code" => "NGN", "is_active" => 1],
        ["name" => "Omani rial", "sign" => ".ع.ر","code" => "OMR"],
        ["name" => "Pakistani rupee", "sign" => "₨","code" => "PKR"],
        ["name" => "Panamanian balboa", "sign" => "B/.","code" => "PAB"],
        ["name" => "Papua New Guinean kina", "sign" => "K","code" => "PGK"],
        ["name" => "Paraguayan guaraní", "sign" => "₲","code" => "PYG"],
        ["name" => "Peruvian sol", "sign" => "S/.","code" => "PEN"],
        ["name" => "Philippine peso", "sign" => "₱","code" => "PHP"],
        ["name" => "Polish złoty", "sign" => "zł","code" => "PLN"],
        ["name" => "Qatari riyal", "sign" => "ق.ر","code" => "QAR"],
        ["name" => "Rwandan franc", "sign" => "FRw","code" => "RWF"],
        ["name" => "Saint Helena pound", "sign" => "£","code" => "SHP"],
        ["name" => "Samoa", "sign" => "SAT","code" => "WST"],
        ["name" => "São Tomé and Príncipe dobra", "sign" => "Db","code" => "STD"],
        ["name" => "Saudi riyal", "sign" => "﷼","code" => "SAR"],
        ["name" => "Serbian dinar", "sign" => "din","code" => "RSD"],
        ["name" => "Seychellois rupee", "sign" => "SRe","code" => "SCR"],
        ["name" => "Sierra Leonean leone", "sign" => "Le","code" => "SLL"],
        ["name" => "Singapore dollar", "sign" => "$","code" => "SGD"],
        ["name" => "Solomon Islands dollar", "sign" => "Si$","code" => "SBD"],
        ["name" => "Somali shilling", "sign" => "Sh.so.","code" => "SOS"],
        ["name" => "South African rand", "sign" => "R","code" => "ZAR"],
        ["name" => "South Sudanese pound", "sign" => "£","code" => "SSP"],
        ["name" => "Sri Lankan rupee", "sign" => "Rs","code" => "LKR"],
        ["name" => "Sudanese pound", "sign" => ".س.ج","code" => "SDG"],
        ["name" => "Surinamese dollar", "sign" => "$","code" => "SRD"],
        ["name" => "Swazi lilangeni", "sign" => "E","code" => "SZL"],
        ["name" => "Swedish krona", "sign" => "kr","code" => "SEK"],
        ["name" => "Syrian pound", "sign" => "LS","code" => "SYP"],
        ["name" => "New Taiwan dollar", "sign" => "$","code" => "TWD"],
        ["name" => "Tajikistani somoni", "sign" => "SM","code" => "TJS"],
        ["name" => "Tanzanian shilling", "sign" => "TSh","code" => "TZS"],
        ["name" => "Thai baht", "sign" => "฿","code" => "THB"],
        ["name" => "Tongan paʻanga", "sign" => "$","code" => "TOP"],
        ["name" => "Trinidad and Tobago dollar", "sign" => "$","code" => "TTD"],
        ["name" => "Tunisia", "sign" => "ت.د","code" => "TND"],
        ["name" => "Turkish lira", "sign" => "₺","code" => "TRY"],
        ["name" => "Turkmenistan manat", "sign" => "T","code" => "TMT"],
        ["name" => "Ugandan shilling", "sign" => "USh","code" => "UGX"],
        ["name" => "Ukrainian hryvnia", "sign" => "₴","code" => "UAH"],
        ["name" => "United Arab Emirates dirham", "sign" => "إ.د","code" => "AED"],
        ["name" => "Uruguayan peso", "sign" => "$","code" => "UYU"],
        ["name" => "Uzbekistani soʻm", "sign" => "лв","code" => "UZS"],
        ["name" => "Vanuatu vatu", "sign" => "VT","code" => "VUV"],
        ["name" => "Venezuelan bolívar soberano", "sign" => "Bs","code" => "VEF"],
        ["name" => "Vietnamese đồng", "sign" => "₫","code" => "VND"],
        ["name" => "Yemeni rial", "sign" => "﷼","code" => "YER"],
        ["name" => "Zambian kwacha", "sign" => "ZK","code" => "ZMW"],
        ["name" => "RTGS dollar", "sign" => "$","code" => "ZWL"],
        ["name" => "Netherlands Antillean guilder", "sign" => "ƒ","code" => "ANG"],
        ];


        foreach($data as $datum){
            $currency = Currency::where("name", $datum["name"])->first();

            if (!$currency) {
                $currency = new Currency();
                $currency->uid = Str::orderedUuid();
            }   

            $currency->name = $datum["name"];
            $currency->sign = $datum["sign"];
            $currency->code = $datum["code"];
            $currency->is_active = isset($datum["is_active"]) ? $datum["is_active"] : 0;
            $currency->save();
        }
       
    }
}
