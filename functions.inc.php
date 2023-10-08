
<?php 

function getFullnameFromParts($surname, $name, $patronomyc){
    return $surname . " " . $name . " " . $patronomyc;
}
print_r (getFullnameFromParts("Шварцнегер","Арнольд","Густавович"));
echo "<br>";

function getPartsFromFullname($fullname){
    $arr = explode(" " , $fullname);
    return [
        'surname' => $arr[0],
        'name' => $arr[1],
        'patronomyc' => $arr[2],
    ];
}
print_r (getPartsFromFullname('Шварцнегер Арнольд Густавович'));

function getShortName($fullName){
    $factor = getPartsFromFullname($fullName);
    $cropName = $factor["name"].' '.mb_substr($factor["surname"],0,1).".";

    return $cropName;
}
print_r (getShortName('Арнольд Шварцнегер'));
echo "<br>";

function getGenderFromName($fullName){
    $degree = getPartsFromFullname($fullName);
    $gender = 0;
    
    //surname
    if (mb_substr($degree["surname"],-2,2) == "ва"){
        $gender = -1;
    } elseif (mb_substr($degree["surname"],-1,1) == "в"){
        $gender = 1;
    } else {
        $gender = 0;
    }
    
    //name
    $genderName = mb_substr($degree["name"],-1,1);

    if ($genderName == "a"){
        $gender = -1;
    } elseif ($genderName == "н" || $genderName == "й"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    //patronomyc
    if (mb_substr($degree["patronomyc"],-3,3) == "вна"){
        $gender = -1;
    } elseif (mb_substr($degree["patronomyc"],-2,2) == "ич"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    if (($gender <=> 0) === 1){
        return "Male";
    } elseif (($gender <=> 0) === -1){
        return "Female";
    } else {
        return "Undefined";
    }

}
print_r (getGenderFromName('Арнольд Шварценегер Густавович'));
echo "<br>";

function getGenderDescription($array){

    $male = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Male");
    });

    $female = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Female");
    });

    $undef = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Undefined");
    });


    $sum = count($male) + count($female) + count($undef);
    $maleCheck =  round(count($male) / $sum * 100,2);
    $femaleCheck = round(count($female) / $sum * 100, 2);
    $undefCheck = round(count($undef) / $sum  * 100,2);

    echo <<<HEREDOC
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $maleCheck%<br>
    Женщины - $femaleCheck%<br>
    Не удалось определить - $undefCheck%<br>
    HEREDOC;

}
echo "<br>";


function getPerfectPartner($surname, $name, $patronomyc, $array){

    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $mainGender = getGenderFromName($fullName);   

    $randPerson = $array[rand(0,count($array)-1)]["fullname"];
    $randGender = getGenderFromName($randPerson);
    

    while ($mainGender == $randGender || $randGender === "Undefined"){
        $randPerson = $array[rand(0,count($array)-1)]["fullname"];
        $randGender = getGenderFromName($randPerson);
    }


    $aboutMainPerson = getShortName($fullName);
    $aboutRandPerson = getShortName($randPerson);
    $percent = rand(50,100)+rand(0,99)/100;


    echo <<<HEREDOC
    $aboutMainPerson + $aboutRandPerson =<br>
    ♡ Идеально на $percent% ♡
    HEREDOC;

}


?>