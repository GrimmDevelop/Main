
<div class="row">
    <div class="col-md-12">
        <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <a href ng-click="letters()">Letters</a>
    </div>
    <div class="col-md-8">
        Export letters
    </div>
</div>
