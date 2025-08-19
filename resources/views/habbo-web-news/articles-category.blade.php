<!-- Articles category page - WCAG 2.1 AA compliant and performance optimized -->
<main role="main" aria-labelledby="category-heading">
    <!-- Category header with proper accessibility -->
    <header class="news-category__header">
        <h1 id="category-heading" class="news-category__title">
            Articles - {{ $category->translate ?? $category->name }}
        </h1>
        
        <!-- Category navigation with proper semantics -->
        <nav class="news-category__navigation" 
             role="navigation" 
             aria-label="Navigation par catégories d'articles">
            <h2 class="visually-hidden">Filtrer par catégorie</h2>
            <ul class="news-category__list" role="list">
                @foreach ($categories as $articleCategory)
                    <li class="news-category__item" role="listitem">
                        @if ($articleCategory->name == $category->name)
                            <a href="/community/category/{{ $articleCategory->name }}"
                               class="news-category__link news-category__link--active"
                               aria-current="page"
                               aria-label="Catégorie actuelle : {{ $articleCategory->translate ?? $articleCategory->name }}">
                                {{ $articleCategory->translate ?? $articleCategory->name }}
                            </a>
                        @else
                            <a href="/community/category/{{ $articleCategory->name }}"
                               class="news-category__link"
                               aria-label="Voir les articles de la catégorie {{ $articleCategory->translate ?? $articleCategory->name }}">
                                {{ $articleCategory->translate ?? $articleCategory->name }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </header>
    <!-- Articles list with full accessibility -->
    <section class="articles-category-list" aria-label="Liste des articles de la catégorie {{ $category->translate ?? $category->name }}">
        @forelse ($articles as $index => $articleContent)
            @php
                $articleUrl = isset($articleContent->roomId) && $articleContent->roomId != 0 
                    ? "/hotel?room={$articleContent->roomId}" 
                    : "/community/article/{$articleContent->id}/content";
                $isExternal = isset($articleContent->roomId) && $articleContent->roomId != 0;
                $publishDate = $articleContent->createdAt ?? $articleContent->timestamp;
            @endphp
            
            <article class="news-header" 
                     role="article" 
                     aria-labelledby="article-title-{{ $articleContent->id }}"
                     itemscope 
                     itemtype="https://schema.org/NewsArticle">
                
                <!-- Article image with proper accessibility -->
                <a href="{{ $articleUrl }}" 
                   class="news-header__link news-header__banner"
                   aria-describedby="article-title-{{ $articleContent->id }}"
                   @if($isExternal) 
                       rel="noopener" 
                       target="_blank"
                       aria-label="{{ $articleContent->title }} (s'ouvre dans un nouvel onglet)"
                   @endif>
                    <figure class="news-header__viewport" 
                            role="img" 
                            aria-labelledby="article-title-{{ $articleContent->id }}">
                        <!-- Responsive images with lazy loading -->
                        <picture>
                            <source media="(max-width: 480px)" 
                                    srcset="{{ $articleContent->thumbnailUrl ?? $articleContent->image }}" 
                                    type="image/jpeg">
                            <img src="{{ $articleContent->imageUrl ?? $articleContent->image }}" 
                                 alt="{{ $articleContent->title }} - Image d'illustration"
                                 class="news-header__image news-header__image--featured"
                                 loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                 decoding="async"
                                 itemprop="image">
                        </picture>
                        <!-- Hidden caption for screen readers -->
                        <figcaption class="visually-hidden">
                            Image d'illustration pour l'article "{{ $articleContent->title }}"
                        </figcaption>
                    </figure>
                </a>
                
                <!-- Article title with proper heading hierarchy -->
                <header class="news-header__wrapper">
                    <h2 id="article-title-{{ $articleContent->id }}" 
                        class="news-header__title"
                        itemprop="headline">
                        <a href="{{ $articleUrl }}" 
                           class="news-header__link"
                           @if($isExternal) 
                               rel="noopener" 
                               target="_blank"
                               aria-label="{{ $articleContent->title }} (s'ouvre dans un nouvel onglet)"
                           @endif>
                            {{ $articleContent->title }}
                        </a>
                    </h2>
                </header>
                
                <!-- Article metadata -->
                <aside class="news-header__wrapper news-header__info" 
                       role="complementary" 
                       aria-label="Informations sur l'article">
                    <!-- Publication date with proper datetime -->
                    <time class="news-header__date" 
                          datetime="{{ isset($publishDate) ? date('c', is_numeric($publishDate) ? $publishDate : strtotime($publishDate)) : date('c') }}"
                          itemprop="datePublished">
                        <span class="visually-hidden">Publié le </span>
                        {{ isset($publishDate) ? date('j F Y', is_numeric($publishDate) ? $publishDate : strtotime($publishDate)) : date('j F Y') }}
                    </time>
                    
                    <!-- Article categories -->
                    @if(isset($articleContent->categories) && count($articleContent->categories) > 0)
                        <nav class="news-header__categories" 
                             role="navigation" 
                             aria-label="Catégories de l'article">
                            <span class="visually-hidden">Catégories : </span>
                            <ul class="news-header__categories-list" role="list">
                                @foreach ($articleContent->categories as $articleCategory)
                                    <li class="news-header__category" role="listitem">
                                        <a href="/community/category/{{ $articleCategory->name }}"
                                           class="news-header__category__link"
                                           aria-label="Voir tous les articles de la catégorie {{ $articleCategory->translate ?? $articleCategory->name }}">
                                            {{ $articleCategory->translate ?? $articleCategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    @endif
                </aside>
                
                <!-- Article summary -->
                <div class="news-header__wrapper news-header__content">
                    <p class="news-header__summary" itemprop="description">
                        {{ $articleContent->description ?? $articleContent->getExcerpt(150) }}
                    </p>
                    
                    <!-- Read more link with accessibility -->
                    <a href="{{ $articleUrl }}" 
                       class="news-header__read-more"
                       aria-label="Lire l'article complet : {{ $articleContent->title }}"
                       @if($isExternal) rel="noopener" target="_blank" @endif>
                        Lire la suite
                        @if($isExternal)
                            <span aria-hidden="true">↗</span>
                            <span class="visually-hidden">(nouvel onglet)</span>
                        @endif
                    </a>
                </div>
                
                <!-- Structured data -->
                <meta itemprop="url" content="{{ url($articleUrl) }}">
                <meta itemprop="dateModified" content="{{ isset($publishDate) ? date('c', is_numeric($publishDate) ? $publishDate : strtotime($publishDate)) : date('c') }}">
                @if(isset($articleContent->author))
                    <meta itemprop="author" content="{{ $articleContent->author->username ?? $articleContent->author }}">
                @endif
            </article>
        @empty
            <!-- Empty state with accessibility -->
            <div class="articles-empty" role="status" aria-live="polite">
                <h2 class="articles-empty__title">Aucun article dans cette catégorie</h2>
                <p class="articles-empty__message">
                    Il n'y a actuellement aucun article dans la catégorie "{{ $category->translate ?? $category->name }}". 
                    <a href="/community" class="articles-empty__link">Découvrez d'autres articles</a>
                </p>
            </div>
        @endforelse
    </section>

    <!-- Pagination with full accessibility -->
    @if(($page > 1) || ($articles->count() == 10))
        <nav class="articles-pagination" 
             role="navigation" 
             aria-label="Navigation entre les pages d'articles">
            <h2 class="visually-hidden">Pages d'articles</h2>
            <ul class="pagination-list" role="list">
                @if($page > 1)
                    <li class="pagination-item" role="listitem">
                        <a href="/community/category/{{ $category->name }}/{{ $page - 1 }}" 
                           class="pagination-link pagination-link--previous"
                           aria-label="Page précédente (page {{ $page - 1 }})"
                           rel="prev">
                            <span aria-hidden="true">‹</span>
                            <span class="pagination-text">Précédent</span>
                        </a>
                    </li>
                @endif
                
                <!-- Current page indicator -->
                <li class="pagination-item pagination-item--current" role="listitem">
                    <span class="pagination-current" aria-current="page">
                        <span class="visually-hidden">Page actuelle, </span>
                        Page {{ $page }}
                    </span>
                </li>
                
                @if($articles->count() == 10)
                    <li class="pagination-item" role="listitem">
                        <a href="/community/category/{{ $category->name }}/{{ $page + 1 }}" 
                           class="pagination-link pagination-link--next"
                           aria-label="Page suivante (page {{ $page + 1 }})"
                           rel="next">
                            <span class="pagination-text">Suivant</span>
                            <span aria-hidden="true">›</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
    
    <!-- Back to all articles link -->
    <div class="category-actions">
        <a href="/community" 
           class="btn btn-secondary category-back-link"
           aria-label="Retourner à la liste de tous les articles">
            ← Tous les articles
        </a>
    </div>
</main>

<!-- Performance optimization: preload pagination pages -->
@if($page > 1)
    <link rel="prefetch" href="/community/category/{{ $category->name }}/{{ $page - 1 }}">
@endif
@if($articles->count() == 10)
    <link rel="prefetch" href="/community/category/{{ $category->name }}/{{ $page + 1 }}">
@endif

<!-- Accessibility: Skip to next section -->
<a href="#footer" class="skip-link">Passer au pied de page</a>

<!-- Add required CSS classes for accessibility -->
<style>
.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

.skip-link {
    position: absolute;
    top: -40px;
    left: 6px;
    background: #1e7cf7;
    color: white;
    padding: 8px;
    text-decoration: none;
    border-radius: 4px;
    z-index: 1000;
}

.skip-link:focus {
    top: 6px;
}
</style>