<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{url('/koszyk')}}</loc>
        <lastmod>2019-02-08T11:23:17+00:00</lastmod>
        <changefreq>mothly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{url('')}}</loc>
        <lastmod>2019-02-08T11:23:17+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{url('/produkty')}}</loc>
        <lastmod>2019-02-08T11:23:17+00:00</lastmod>
        <changefreq>mothly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{url('/checkout')}}</loc>
        <lastmod>2019-02-08T11:23:17+00:00</lastmod>
        <changefreq>mothly</changefreq>
        <priority>0.4</priority>
    </url>
    @foreach ($categories as $category)
        @if($category->isParent())
            <url>
                <loc>{{url('/kategoria/'.$category->search)}}</loc>
                <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.5</priority>
            </url>
        @else
            @php
                $parent_category = $category->getParent();
            @endphp
            <url>
                <loc>{{url('/kategoria/'.$parent_category->search.'/podkategoria/'.$category->search)}}</loc>
                <lastmod>{{ $category->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
            @endif
    @endforeach
    @foreach ($products as $product)
        <url>
            <loc>{{route('index.product', ['id' => $product->macma_id, 'product'=> \App\Services\Help::slugify($product->name)])}}</loc>
            <lastmod>{{ $product->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>dayly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
    @foreach ($pages as $page)
        <url>
            <loc>{{url('/strona/'.$page->url)}}</loc>
            <lastmod>{{ $product->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>