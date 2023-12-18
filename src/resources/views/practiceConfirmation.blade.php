<!doctype html>
<html lang="en">
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Výkaz o praxi</title>
</head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; font-size: 12px }
        table { border: 1px solid black; width:90%; margin: 0 auto; padding: 10px;border-collapse: collapse;}
        table.non-border {border:0;}
        table h4 {margin:4px 0 8px 0;}
        .first {margin-bottom: 30px;}
        .first div {margin-bottom: 2px;}
        .main-table-heading {width:100%;}
        .main-table-heading th {width: 100%; text-align: center;}
        .records-table tr th {width:25%;border:1px solid black;}
        .records-table tr th.date {width:15%;}
        .records-table tr th.hours {width:10%;}
        .records-table tr th.description {width:35%;}
        .records-table tr td {text-align: center;border:1px solid black;}
        .note {width:50%;font-weight: normal;margin:0 auto;}
        .note-container {
            width:100%;
            text-align: center;
        }
        td.main-cell {
            width:45%;
            height: 40px;
        }
        .page_break { page-break-before: always; }
    </style>
<body>
    
    <h2>Výkaz o vykonanej odbornej praxi</h2>

    <div>
        <table class="first">
            <tr>
                <td>
                    <div><h4>ŠTUDENT</h4></div>
                    <div>meno a priezvisko: {{$user->first_name}} {{$user->last_name}}</div>
                    <div>študijný program: {{$program->name}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><h4>ORGANIZÁCIA</h4></div>
                    <div>názov a sídlo organizácie/pracoviska praxe: {{$company->name}}, {{$company->street}} {{$company->house_number}},
                    {{$company->zip_code}} {{$company->city}}</div>
                    <div>meno a priezvisko tútora praxe: {{$companyEmployee->user->first_name}} {{$companyEmployee->user->last_name}}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div><h4>OBDOBIE ABSOLVOVANIA ODBORNEJ PRAXE</h4></div>
                    <div>dátum nástupu na prax: {{$practice->from}}</div>
                    <div>dátum ukončenia praxe: {{$practice->to}}</div>
                    <div>celkový počet hodín absolvovanej praxe:</div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table class="records-table">
            <tr class="main-table-heading">
                <th colspan="4"><h4>PRACOVNÉ ČINNOSTI</h4></th>
            </tr>
            <tr>
                <th class="date">Dátum</th>
                <th class="description">Popis činnosti</th>
                <th class="hours">Počet hodín</th>
                <th>Podpis tútora praxe</th>
            </tr>
            @foreach($practiceRecords as $record)
                <tr>
                    <td>
                        {{ $record['from'] }} <br> {{ $record['to'] }}
                    </td>
                    <td>
                        {{ $record['description'] }}
                    </td>
                    <td>
                        {{ $record['hours'] }}
                    </td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="page_break"></div>
    <div>
        <h2>Hodnotenie študenta</h2>
        <div class="note-container">
            <h5 class="note"><i>každé kritérium ohodnoťte zakrúžkovaním číslice  1 - najmenej vyhovujúce,  5 - najviac vyhovujúce</i></h6>
        </div>
        <table class="records-table">
            <tr>
                <td class="main-cell">organizovanie a plánovanie práce</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť pracovať v tíme</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť učiť sa</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">úroveň digitálnej gramotnosti</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">kultivovanosť prejavu</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">používanie zaužívaných výrazov</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">prezentovanie</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">samostatnosť</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">adaptabilita</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">flexibilita</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť improvizovať</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť prijímať rozhodnutia</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť niesť zodpovednosť</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">dodržovanie etických zásad</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
            <tr>
                <td class="main-cell">schopnosť jednania s ľudmi</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>n/a</td>
            </tr>
        </table>

        <table class="non-border">
            <tr>
                <td><span style="font-weight: bold">Celkové skóre<span> (doplní tútor)...................</td>
            </tr>
            <br>
            <tr>
                <td style="font-weight: bold">Podpis tútora odbornej praxe:</td>
            </tr>
            <br>
            <tr>
                <td style="font-weight: bold">Dátum a pečiatka organizácie:</td>
            </tr>
        </table>
    </div>

 

</body>
</html>