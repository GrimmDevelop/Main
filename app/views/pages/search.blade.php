@extends('layout')

@section('body')
<form action="{{ url('search') }}" method="post" class="form-horizontal">
    <div class="control-group">
        <span class="control-label">Datum</span>
        <div class="controls">
            <input type="text" class="" name="time[start]" value="{{ Input::get('time.start') }}" placeholder="von" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Das Startdatum muss im Format dd.mm.jjjj eingegeben werden (z.B.: 13.06.1790)."><i class="glyphicon glyphicon-info-sign"></i></button>
            <br style="margin-bottom: 10px;" />
            <input type="text" name="time[end]" value="{{ Input::get('time.end') }}" placeholder="bis" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Das Enddatum muss im Format dd.mm.jjjj eingegeben werden (z.B.: 13.06.1790)."><i class="glyphicon glyphicon-info-sign"></i></button>
        </div>
    </div>
    <div class="control-group">
        <span class="control-label">Absender</span>
        <div class="controls">
            <input type="text" name="send[name]" value="{{ Input::get('send.name') }}" placeholder="Name" autocomplete="off" class="typeahead" style="margin-top: 0;" />
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:fill('send[name]', 'Grimm, Jacob');">Grimm, Jacob</a></li>
                    <li><a href="javascript:fill('send[name]', 'Grimm, Wilhelm');">Grimm, Wilhelm</a></li>
                </ul>
            </div>
            <br style="margin-bottom: 10px;" />
            <input type="text" name="send[location]" value="{{ Input::get('send.location') }}" placeholder="Ort" autocomplete="off" class="typeahead" style="margin-top: 0;" />
        </div>
    </div>
    <div class="control-group">
        <span class="control-label">Empfänger</span>
        <div class="controls">
            <input type="text" name="receive[name]" value="{{ Input::get('receive.name') }}" placeholder="Name" autocomplete="off" class="typeahead" style="margin-top: 0;" />
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-down"></i></button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:fill('receive[name]', 'Grimm, Jacob');">Grimm, Jacob</a></li>
                    <li><a href="javascript:fill('receive[name]', 'Grimm, Wilhelm');">Grimm, Wilhelm</a></li>
                </ul>
            </div>
            <br style="margin-bottom: 10px;" />
            <input type="text" name="receive[location]" value="{{ Input::get('receive.location') }}" placeholder="Ort" autocomplete="off" class="typeahead" style="margin-top: 0;" />
        </div>
    </div>
    <div class="control-group">
        <span class="control-label">Allgemein</span>
        <div class="controls">
            <input type="text" name="letter[inc]" value="{{ Input::get('letter.inc') }}" placeholder="Briefbeginn" autocomplete="off" class="typeahead" style="margin-top: 0;" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Suche von einzelnen Teilwörtern im Briefbeginn. Die Teilwörter werden mit einem UND verknüpft!">
                <i class="glyphicon glyphicon-info-sign"></i>
            </button>

            <br style="margin-bottom: 10px;" />

            <input type="text" name="letter[prints]" value="{{ Input::get('letter.prints') }}" placeholder="Gedruckt in" autocomplete="off" class="typeahead" style="margin-top: 0;" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Vorgeschlagen werden vollständige Druckstandorte. Es können bei manueler Eingabe auch nur Städte (z.B. 'Berlin') angegeben werden.">
                <i class="glyphicon glyphicon-info-sign"></i>
            </button>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <input type="text" name="letter[nr]" value="{{ Input::get('letter.nr') }}" placeholder="Briefnummer" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Suche nach einem bestimmten Brief per Nummer. Es werden evtl. mehrere Briefe angezeigt, falls in vorherigen Versionen Nummern anders vergeben waren. Alle anderen Sucheingaben werden ignoriert!">
                <i class="glyphicon glyphicon-info-sign"></i>
            </button>

            <br style="margin-bottom: 10px;" />

            <input type="text" name="letter[hw_location]" value="{{ Input::get('letter.hw_location') }}" placeholder="Handschriftenstandorte" autocomplete="off" class="typeahead" style="margin-top: 0;" />
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Vorgeschlagen werden vollständige Handschriftenstandorte. Es können bei manueler Eingabe auch nur Städte (z.B. 'Berlin') angegeben werden.">
                <i class="glyphicon glyphicon-info-sign"></i>
            </button>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Briefe suchen</button>
        </div>
    </div>
</form>

@if(isset($result))
Es wurden {{ $count }} Briefe gefunden. <i class="icon-question-sign" data-toggle="tooltip" title="Es werden maximal 100 Suchergebnisse angezeigt!"></i><br /><br />
<table>
    {% for letter in result %}
    <tr>
        <th colspan="2">
            {{ letter.absendeort != '' ? letter.absendeort . ', ' : '' }}
            {{ letter.absort_ers != '' ? '[' . letter.absort_ers . '], ' : '' }}
            {{ letter.datum == '' ? '[Datum unbekannt]' : letter.datum }}:
            {{ letter.absender == '' ? '[Absender unbekannt]' : letter.absender }}
            an {{ letter.empfaenger == '' ? '[Empfänger unbekannt]' : letter.empfaenger }}
        </th>
    </tr>
    {{ briefFeld(letter, "inc", "Briefbeginn:")|raw }}
    {{ briefFeld(letter, "empf_ort", "Empfangsort:")|raw }}
    {{ briefFeld(letter, "hs", "Handschrift:")|raw }}
    {{ briefFeld(letter, "couvert", "Couvert:")|raw }}
    {{ briefFeld(letter, "beilage", "Beilage(n):")|raw }}
    {{ briefFeld(letter, "konzept", "Konzept(e):", 2)|raw }}
    {{ briefFeld(letter, "abschrift", "Abschrift(en):")|raw }}
    {{ briefFeld(letter, "abschr_2", "")|raw }}
    {{ briefFeld(letter, "abschr_3", "")|raw }}
    {{ briefFeld(letter, "abschr_4", "")|raw }}
    {{ briefFeld(letter, "ausg_notiz", "Ausgangsnotiz:")|raw }}
    {{ briefFeld(letter, "erschl_aus", "Erschlossen aus:")|raw }}
    {{ briefFeld(letter, "empf_verm", "Empfangsvermerk:")|raw }}
    {{ briefFeld(letter, "antw_verm", "Antwortvermerk:")|raw }}
    {{ briefFeld(letter, "dr", "Abgedruckt in:", 7)|raw }}
    {{ briefFeld(letter, "verz_in", "Verzeichnet in:")|raw }}
    {{ briefFeld(letter, "faks", "Faksimile:")|raw }}
    {{ briefFeld(letter, "auktkat", "Verkauf / Verkäufe:", 3)|raw }}
    {{ briefFeld(letter, "zusatz", "Bemerkungen:", 2)|raw }}
    <tr>
        <td width="120" align="right" valign="top">Nummer(n):</td>
        <td valign="top">{{ letter.id }}.{% if letter.nr_1992 != 0 and letter.id != letter.nr_1992 %} [{{ letter.nr_1992 }}.]{% endif %}</td>
    </tr>
    {{ briefFeld(letter, "code", "Datumscode:")|raw }}
    {% endfor %}
</table>
@endif

<script>
    function fill(target, value) {
        var targetEl = document.getElementsByName(target);
        if(targetEl.length > 0) {
            targetEl[0].value = value;
            $(targetEl[0]).change();
        }
    }

    $(function() {
        $('.typeahead').each(function() {
            var $this = $(this);
            $this.typeahead({
                source: function(query, process) {
                    $.getJSON("{{ url('search/typeahead') }}?field=" + $this.attr('name') + "&query=" + query, function(data) {
                        process(data);
                    });
                }
            });
        });
    });
</script>
@stop
