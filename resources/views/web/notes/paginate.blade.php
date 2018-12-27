<!doctype html>
<html lang=jp">
    <head>
        @include('layouts.web.head')
    </head>
    <body>
        @include('layouts.web.header')
        <div id="showNotes">
            <div class="no_border_card">
                <div class="title">
                    <div class="table_view">
                        <a class="icon" href="/notes">
                            <img src="/img/top_note.png" alt="">
                        </a>
                        <div class="text">
                            <a href="/notes">
                            {{ 'Notes' }}
                            </a>
                        </div>
                        <div class="link">
                            <a href="/notes">
                                <img src="/img/note_search.png" alt="">
                            </a>
                        </div>
                        <div class="link">
                            <a href="/notes/create">
                                <img src="/img/note_create.png" alt="">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="content">
                    @if($flag == 'isBest')
                    <a class="category_wrapper" href="/bestnotes">
                        <img class="category_icon" src="/img/categories/best.png" alt="bestnotes">
                        <p class="category_name">Best Notes</p>      
                    </a>    
                    @elseif($flag == 'category')
                    <div class="category_wrapper" href="/bestnotes">
                        <img class="category_icon" src="/img/categories/category.png" alt="category">
                        <p class="category_name">{{ $category->name }}</p>      
                    </div>
                    @elseif($flag == 'tag')
                    <div class="category_wrapper" href="/bestnotes">
                        <img class="category_icon" src="/img/categories/tag.png" alt="tag">
                        <p class="category_name">{{ $tag->name }}</p>      
                    </div>
                    @elseif($flag == 'author')
                    <div class="category_wrapper" href="/users/{{ $user->id }}/notes">
                        <img class="category_icon" src="/img/categories/tag.png" alt="user">
                        <p class="category_name">{{ $user->name }}</p>      
                    </div>
                    @else
                    <div class="category_wrapper" href="/countries/{{ $country->id }}/notes">
                        <img class="category_icon" src="/img/flags/{{ $country->english_name }}.png" alt="country">
                        <p class="category_name">{{ $country->name }}</p>
                    </div>
                    @endif
                    <p class="numOfNotes">{{ $notes->count() }}件のノート</p>
                    <div class="sp">{{ $notes->links() }}</div>
                    <ul class="note_view">
                    @foreach($notes as $note)
                    @include('layouts.web.notes')
                    @endforeach    
                    </ul>
                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </body>
</html>
