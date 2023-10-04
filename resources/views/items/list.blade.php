<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- css -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('/css/mercari.css') }}" />
    <!-- script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <title>Rakus Items</title>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('item.index') }}">Rakus Items</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <div>
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Logout <i class="fa fa-power-off"></i></button>
                        </form>
                    </li>
                </ul>
                <p class="navbar-text navbar-right">
                    <span id="loginName">user: {{Auth::user()->name }}</span>
                </p>
            </div>
        </div>
    </nav>

    <div id="main" class="container-fluid">
        <!-- addItem link -->
        <div id="addItemButton">
            <a class="btn btn-default" href="{{ route('item.create') }}"><i class="fa fa-plus-square-o"></i> Add New
                Item</a>
        </div>
        @if (session('danger'))
            <div class="alert alert-danger" role="alert">
                {{ session('danger') }}
            </div>
        @endif

        <!-- 検索フォーム -->
        <div id="forms">
            <form action="{{ route('items.search') }}" class="form-inline" role="form" method="GET">
                <div class="form-group">
                    <input type="input" class="form-control" id="itemName" name="itemName" placeholder="item name" />
                </div>
                <div class="form-group"><i class="fa fa-plus"></i></div>
                <div class="form-group">
                    <select id="parent_category_id" name="parent_category_id" class="form-control">
                        <option value="0">- parentCategory -</option>
                        @foreach ($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                        @endforeach
                    </select>
                    <select id="child_category_id" name="child_category_id" class="form-control">
                        <option value="0">- childCategory -</option>
                        @foreach ($childCategories as $childCategory)
                            <option value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                        @endforeach
                    </select>
                    <select id="grandchild_category_id" name="grandchild_category_id" class="form-control">
                        <option value="0">- grandChild -</option>
                        @foreach ($grandChildCategories as $grandChildCategory)
                            <option value="{{ $grandChildCategory->id }}">{{ $grandChildCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><i class="fa fa-plus"></i></div>
                <div class="form-group">
                    <input type="text" class="form-control" id="brand" name="brand" placeholder="brand" />
                </div>
                <div class="form-group"></div>
                <button type="submit" class="btn btn-default"><i class="fa fa-angle-double-right"></i> search</button>
            </form>
        </div>

        <!-- pagination -->
        {{-- <div class="pages">
            <nav class="page-nav">
                <ul class="pager">
                    <li class="previous"><a href="#">&larr; prev</a></li>
                    <li class="next"><a href="#">next &rarr;</a></li>
                </ul>
            </nav>
        </div> --}}

        <!-- table -->
        <div class="table-responsive">
            <table id="item-table" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th>name</th>
                        <th>price</th>
                        <th>category</th>
                        <th>brand</th>
                        <th>cond</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        {{-- categoryのname_allを区切り文字で要素に分ける --}}
                        @php
                            $categoryNameAll = $item->name_all;
                            $category = explode('/', $categoryNameAll);
                        @endphp
                        <tr>
                            <td class="item-name"><a
                                    href="{{ route('item.show', ['item' => $item->item_id]) }}">{{ $item->item_name }}</a>
                            </td>
                            <td class="item-price">{{ $item->price }}</td>
                            <td class="item-category">
                                <a href="">{{ isset($category[0]) ? $category[0] : '' }}</a> / <a
                                    href="">{{ isset($category[1]) ? $category[1] : '' }}</a> / <a
                                    href="">{{ $item->category_name }}</a>
                            </td>
                            <td class="item-brand"><a href="">{{ $item->brand }}</a></td>
                            <td class="item-condition">{{ $item->condition_id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $items->links('vendor.pagination.bootstrap-4') }}
        {{-- <!-- pagination -->
        <div class="pages">
            <nav class="page-nav">
                <ul class="pager">
                    <li class="previous"><a href="#">&larr; prev</a></li>
                    <li class="next"><a href="#">next &rarr;</a></li>
                </ul>
            </nav>
            <!-- ページ番号を指定して表示するフォーム -->
            <div id="select-page">
                <form class="form-inline">
                    <div class="form-group">
                        <div class="input-group col-xs-6">
                            <label></label>
                            <input type="text" class="form-control" />
                            <!-- 総ページ数 -->
                            <div class="input-group-addon">/ 20</div>
                        </div>
                        <div class="input-group col-xs-1">
                            <button type="submit" class="btn btn-default">Go</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>

    <script src="{{ asset('/js/category.js') }}" type="module"></script>
</body>

</html>
