<!-- Article detail page - WCAG 2.1 AA compliant and performance optimized -->
<main role="main">
    <article itemscope 
             itemtype="https://schema.org/NewsArticle"
             role="article"
             aria-labelledby="article-title">
        
        <!-- Breadcrumb navigation for accessibility -->
        <nav aria-label="Fil d'Ariane" class="breadcrumb">
            <ol class="breadcrumb-list" role="list">
                <li class="breadcrumb-item" role="listitem">
                    <a href="/community" class="breadcrumb-link">Articles</a>
                </li>
                @if(isset($article->category))
                    <li class="breadcrumb-item" role="listitem">
                        <a href="/community/category/{{ $article->category->name }}" 
                           class="breadcrumb-link">
                            {{ $article->category->translate ?? $article->category->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item breadcrumb-item--current" role="listitem">
                    <span aria-current="page" class="breadcrumb-current">
                        {{ $article->title }}
                    </span>
                </li>
            </ol>
        </nav>
        
        <header class="news-header news-header--single">
            <!-- Hero image with proper accessibility -->
            <div class="news-header__banner">
                <figure class="news-header__viewport" role="img" aria-labelledby="article-title">
                    <img src="{{ $article->imageUrl ?? $article->image }}"
                         alt="{{ $article->title }} - Image principale de l'article"
                         class="news-header__image news-header__image--featured"
                         loading="eager"
                         decoding="async"
                         itemprop="image">
                    <figcaption class="visually-hidden">
                        Image principale illustrant l'article "{{ $article->title }}"
                    </figcaption>
                </figure>
            </div>
            
            <!-- Social sharing component with proper accessibility -->
            <div class="social-share" role="complementary" aria-label="Partager cet article">
                <habbo-social-share type="news" 
                                    aria-label="Boutons de partage sur les réseaux sociaux">
                </habbo-social-share>
            </div>
            
            <!-- Article title -->
            <h1 id="article-title" 
                class="news-header__wrapper news-header__title"
                itemprop="headline">
                {{ $article->title }}
            </h1>
            
            <!-- Article metadata -->
            <aside class="news-header__wrapper news-header__info" 
                   role="complementary" 
                   aria-label="Informations sur l'article">
                <!-- Publication date with proper datetime -->
                <time class="news-header__date" 
                      datetime="{{ isset($article->createdAt) ? date('c', is_numeric($article->createdAt) ? $article->createdAt : strtotime($article->createdAt)) : (isset($article->timestamp) ? date('c', $article->timestamp) : date('c')) }}"
                      itemprop="datePublished">
                    <span class="visually-hidden">Publié le </span>
                    {{ isset($article->createdAt) ? date('j F Y', is_numeric($article->createdAt) ? $article->createdAt : strtotime($article->createdAt)) : (isset($article->timestamp) ? date('j F Y', $article->timestamp) : date('j F Y')) }}
                </time>
                
                <!-- Author information -->
                @if(isset($article->author))
                    <address class="news-header__author" itemprop="author">
                        <span class="visually-hidden">Par </span>
                        {{ $article->author->username ?? $article->author }}
                    </address>
                @endif
                
                <!-- Article categories -->
                @if(isset($article->categories) && count($article->categories) > 0)
                    <nav class="news-header__categories" 
                         role="navigation" 
                         aria-label="Catégories de l'article">
                        <h2 class="visually-hidden">Catégories</h2>
                        <ul class="news-header__categories-list" role="list">
                            @foreach ($article->categories as $articleCategory)
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
            
            <!-- Article summary/description -->
            @if(isset($article->description) && !empty($article->description))
                <div class="news-header__wrapper news-header__summary">
                    <p class="article-description" itemprop="description">
                        {{ $article->description }}
                    </p>
                </div>
            @endif
        </header>
        <!-- Article content with proper accessibility -->
        <div class="news-article" 
             role="main" 
             aria-label="Contenu principal de l'article"
             itemprop="articleBody">
            <div class="article-content">
                {!! $article->content !!}
            </div>
        </div>
        
        <!-- Article footer with related and latest articles -->
        <footer class="news-footer" role="complementary">
            <div class="news-footer__container">
                <!-- Related articles -->
                @if(isset($related) && count($related) > 0)
                    <aside class="news-box news-box--related" 
                           role="complementary" 
                           aria-labelledby="related-heading">
                        <h2 id="related-heading" class="news-box__title">
                            Articles liés
                        </h2>
                        <ul class="news-box__list" role="list">
                            @foreach ($related as $relatedContent)
                                <li class="news-box__item" role="listitem">
                                    <article class="related-article" itemscope itemtype="https://schema.org/NewsArticle">
                                        <h3 class="news-box__article-title">
                                            <a href="/community/article/{{ $relatedContent->id }}/content" 
                                               class="news-box__link"
                                               aria-label="Lire l'article : {{ $relatedContent->title }}"
                                               itemprop="url">
                                                <span itemprop="headline">{{ $relatedContent->title }}</span>
                                            </a>
                                        </h3>
                                        <time class="news-box__date" 
                                              datetime="{{ isset($relatedContent->createdAt) ? date('c', is_numeric($relatedContent->createdAt) ? $relatedContent->createdAt : strtotime($relatedContent->createdAt)) : date('c') }}"
                                              itemprop="datePublished">
                                            <span class="visually-hidden">Publié le </span>
                                            {{ isset($relatedContent->createdAt) ? date('j F Y', is_numeric($relatedContent->createdAt) ? $relatedContent->createdAt : strtotime($relatedContent->createdAt)) : date('j F Y') }}
                                        </time>
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
                
                <!-- Latest articles -->
                @if(isset($latest) && count($latest) > 0)
                    <aside class="news-box news-box--latest" 
                           role="complementary" 
                           aria-labelledby="latest-heading">
                        <h2 id="latest-heading" class="news-box__title">
                            Derniers articles
                        </h2>
                        <ul class="news-box__list" role="list">
                            @foreach ($latest as $latestContent)
                                <li class="news-box__item" role="listitem">
                                    <article class="latest-article" itemscope itemtype="https://schema.org/NewsArticle">
                                        <h3 class="news-box__article-title">
                                            <a href="/community/article/{{ $latestContent->id }}/content" 
                                               class="news-box__link"
                                               aria-label="Lire l'article : {{ $latestContent->title }}"
                                               itemprop="url">
                                                <span itemprop="headline">{{ $latestContent->title }}</span>
                                            </a>
                                        </h3>
                                        <time class="news-box__date" 
                                              datetime="{{ isset($latestContent->createdAt) ? date('c', is_numeric($latestContent->createdAt) ? $latestContent->createdAt : strtotime($latestContent->createdAt)) : date('c') }}"
                                              itemprop="datePublished">
                                            <span class="visually-hidden">Publié le </span>
                                            {{ isset($latestContent->createdAt) ? date('j F Y', is_numeric($latestContent->createdAt) ? $latestContent->createdAt : strtotime($latestContent->createdAt)) : date('j F Y') }}
                                        </time>
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
            </div>
            
            <!-- Back to articles navigation -->
            <nav class="article-navigation" role="navigation" aria-label="Navigation de l'article">
                <a href="/community" 
                   class="btn btn-secondary article-back-link"
                   aria-label="Retourner à la liste des articles">
                    ← Retour aux articles
                </a>
                @if(isset($article->category))
                    <a href="/community/category/{{ $article->category->name }}" 
                       class="btn btn-outline article-category-link"
                       aria-label="Voir tous les articles de la catégorie {{ $article->category->translate ?? $article->category->name }}">
                        Plus d'articles dans {{ $article->category->translate ?? $article->category->name }}
                    </a>
                @endif
            </nav>
        </footer>
        
        <!-- Structured data -->
        <meta itemprop="url" content="{{ url('/community/article/' . $article->id . '/content') }}">
        <meta itemprop="dateModified" content="{{ isset($article->createdAt) ? date('c', is_numeric($article->createdAt) ? $article->createdAt : strtotime($article->createdAt)) : (isset($article->timestamp) ? date('c', $article->timestamp) : date('c')) }}">
        @if(isset($article->image))
            <meta itemprop="image" content="{{ $article->imageUrl ?? $article->image }}">
        @endif
    </article>
</main>

<!-- Performance optimization: preload related articles -->
@if(isset($related) && count($related) > 0)
    @foreach($related->take(3) as $relatedContent)
        <link rel="prefetch" href="/community/article/{{ $relatedContent->id }}/content">
    @endforeach
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

.breadcrumb {
    margin-bottom: 1rem;
}

.breadcrumb-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item:not(:last-child)::after {
    content: ' / ';
    margin: 0 0.5rem;
    color: #666;
}

/* Focus improvements */
*:focus {
    outline: 2px solid #1e7cf7;
    outline-offset: 2px;
}
</style>