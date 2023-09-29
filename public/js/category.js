// import axios from 'axios';

// 大カテゴリが変更されたときのイベントハンドラ
document.getElementById('parent_category_id').addEventListener('change', function() {
    const parentCategoryId = this.value;

    // Ajaxリクエストを送信
    axios.get(`/get-childcategories?parent_category_id=${parentCategoryId}`)
        .then(function (response) {
            const childCategories = response.data;

            // 中カテゴリのプルダウンをクリア
            const childCategorySelect = document.getElementById('child_category_id');
            childCategorySelect.innerHTML = '';

            // 取得した中カテゴリをプルダウンに追加
            childCategories.forEach(function (childCategory) {
                const option = document.createElement('option');
                option.value = childCategory.id;
                option.text = childCategory.name;
                childCategorySelect.appendChild(option);
            });
        })
        .catch(function (error) {
            console.log(error);
        });
});


// 中カテゴリが変更されたときのイベントハンドラ
document.getElementById('child_category_id').addEventListener('change', function() {
    const childCategoryId = this.value;

    // Ajaxリクエストを送信
    axios.get(`/get-grandchildcategories?child_category_id=${childCategoryId}`)
        .then(function (response) {
            const grandchildCategories = response.data;

            // 小カテゴリのプルダウンをクリア
            const grandchildCategorySelect = document.getElementById('grandchild_category_id');
            grandchildCategorySelect.innerHTML = '';

            // 取得した小カテゴリをプルダウンに追加
            grandchildCategories.forEach(function (grandchildCategory) {
                const option = document.createElement('option');
                option.value = grandchildCategory.id;
                option.text = grandchildCategory.name;
                grandchildCategorySelect.appendChild(option);
            });
        })
        .catch(function (error) {
            console.log(error);
        });
});

