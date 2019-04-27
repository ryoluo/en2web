@extends('layouts.form')
@section('title', ' - Attendance')
@section('content')
<div id="attendance-manager">
    <div class="border_card">
        <div class="title">
            <div class="table_view">
                <div class="text">
                    @if(is_null($mtg))
                    <p>出席管理するイベントを追加</p>
                    @else
                    <p>出席管理中：{{ $mtg->name }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="content">
            @if(is_null($mtg))
                <form method="POST" action="/meeting/add" onsubmit="disableButton()">
                {{ csrf_field() }}
                    <div class="form_view">
                        <div class="property"><p>Event Title</p></div>
                        <div class="value">
                            <input name="name" class="input_text" placeholder="Ex) {{ Carbon\Carbon::now()->month }}月全体MTG" required>
                        </div>
                    </div>
                    <div class="form_view">
                        <div class="button_wrapper">
                            <button type="submit" class="bluebtn" id="disable_button">
                                <p class="button_text">Add</p>
                                <div class="loader">Loading</div>
                            </button>
                        </div>
                    </div>
                </form>
            @else
                @if($mtg->status == 'await')
                <p class="notification">現在、出席ボタンは表示されていません。</p>
                <form method="POST" action="/meeting/{{ $mtg->id }}/toggle">
                    @csrf
                    <div class="form_view">
                        <button type="submit" class="toggle-btn bluebtn">出席ボタンを表示する</button>
                    </div>
                </form>
                <form method="POST" action="/meeting/{{ $mtg->id }}/complete">
                    @csrf
                    <div class="form_view">
                        <button type="submit" class="complete-btn redbtn">出席管理を完了</button>
                    </div>
                </form>
                @else
                <p class="notification">現在、出席ボタンを表示中です。</p>
                <form method="POST" action="/meeting/{{ $mtg->id }}/toggle">
                    @csrf
                    <div class="form_view">
                        <button type="submit" class="toggle-btn bluebtn">出席ボタンを非表示にする</button>
                    </div>
                </form>
                @endif
            @endif
        </div>
    </div>
    @if(!is_null($mtg) && $mtg->status == 'await')
    <form method="POST" action="/meeting/{{ $mtg->id }}/cancel">
        @csrf
        <div class="cancel-wrapper">
            <button type="submit" class="cancel-btn graybtn">出席管理をキャンセル</button>
        </div>
    </form>
    @endif
</div>
@endsection