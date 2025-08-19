<!-- Articles front page - WCAG 2.1 AA compliant and performance optimized -->
<main role="main" aria-labelledby="articles-heading">
    <!-- Screen reader heading -->
    <h1 id="articles-heading" class="visually-hidden">Articles et actualités</h1>
    
    <!-- Articles list -->
    <section class="articles-list" aria-label="Liste des derniers articles">
        @forelse ($set as $index => $article)
            <article class="news-header news-header--column" 
                     role="article" 
                     aria-labelledby="article-title-{{ $article->id }}"
                     itemscope itemtype="https://schema.org/NewsArticle">
                
                <!-- Article image with proper accessibility -->
                @php
                    $articleUrl = isset($article->roomId) && $article->roomId != 0 
                        ? "/hotel?room={$article->roomId}" 
                        : "/community/article/{$article->id}/content";
                    $isExternal = isset($article->roomId) && $article->roomId != 0;
                @endphp
                
                <a href="{{ $articleUrl }}" 
                   class="news-header__link news-header__banner"
                   aria-describedby="article-title-{{ $article->id }}"
                   @if($isExternal) rel="noopener" target="_blank" @endif>
                    <figure class="news-header__viewport" role="img" aria-labelledby="article-title-{{ $article->id }}">
                        <!-- Responsive images with lazy loading -->
                        <picture>
                            <source media="(max-width: 480px)" 
                                    srcset="{{ $article->thumbnailUrl ?? $article->image }}" 
                                    type="image/jpeg">
                            <img src="{{ $article->imageUrl ?? $article->image }}" 
                                 alt="{{ $article->title }} - Image d'illustration"
                                 class="news-header__image"
                                 loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                 decoding="async"
                                 itemprop="image">
                        </picture>
                        <!-- Hidden caption for screen readers -->
                        <figcaption class="visually-hidden">
                            Image d'illustration pour l'article "{{ $article->title }}"
                        </figcaption>
                    </figure>
                </a>
                
                <!-- Article title with proper heading hierarchy -->
                <header class="news-header__wrapper">
                    <h2 id="article-title-{{ $article->id }}" 
                        class="news-header__title"
                        itemprop="headline">
                        <a href="{{ $articleUrl }}" 
                           class="news-header__link"
                           @if($isExternal) 
                               rel="noopener" 
                               target="_blank"
                               aria-label="{{ $article->title }} (s'ouvre dans un nouvel onglet)"
                           @endif>
                            {{ $article->title }}
                        </a>
                    </h2>
                </header>
                
                <!-- Article metadata -->
                <aside class="news-header__wrapper news-header__info" 
                       role="complementary" 
                       aria-label="Informations sur l'article">
                    <!-- Publication date with proper datetime -->
                    <time class="news-header__date" 
                          datetime="{{ date('c', $article->timestamp ?? time()) }}"
                          itemprop="datePublished">
                        <span class="visually-hidden">Publié le </span>
                        {{ date('j F Y', $article->timestamp ?? time()) }}
                    </time>
                    
                    <!-- Article categories -->
                    @if(isset($article->categories) && count($article->categories) > 0)
                        <nav class="news-header__categories" 
                             role="navigation" 
                             aria-label="Catégories de l'article">
                            <span class="visually-hidden">Catégories : </span>
                            <ul class="news-header__categories-list" role="list">
                                @foreach ($article->categories as $category)
                                    <li class="news-header__category" role="listitem">
                                        <a href="/community/category/{{ $category->name }}"
                                           class="news-header__category__link"
                                           aria-label="Voir tous les articles de la catégorie {{ $category->name }}">
                                            {{ $category->translate ?? $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    @endif
                    
                    <!-- Author information -->
                    @if(isset($article->author))
                        <address class="news-header__author" itemprop="author">
                            <span class="visually-hidden">Par </span>
                            {{ $article->author->username ?? $article->author }}
                        </address>
                    @endif
                </aside>
                
                <!-- Article summary -->
                <div class="news-header__wrapper news-header__content">
                    <p class="news-header__summary" itemprop="description">
                        {{ $article->description ?? $article->getExcerpt(150) }}
                    </p>
                    
                    <!-- Read more link with accessibility -->
                    <a href="{{ $articleUrl }}" 
                       class="news-header__read-more"
                       aria-label="Lire l'article complet : {{ $article->title }}"
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
                <meta itemprop="dateModified" content="{{ date('c', $article->timestamp ?? time()) }}">
            </article>
        @empty
            <!-- Empty state with accessibility -->
            <div class="articles-empty" role="status" aria-live="polite">
                <h2 class="articles-empty__title">Aucun article disponible</h2>
                <p class="articles-empty__message">
                    Il n'y a actuellement aucun article à afficher. 
                    Revenez plus tard pour découvrir les dernières actualités !
                </p>
            </div>
        @endforelse
    </section>
    
    <!-- Load more articles (if pagination needed) -->
    @if(isset($hasMore) && $hasMore)
        <div class="articles-pagination" role="navigation" aria-label="Pagination des articles">
            <button type="button" 
                    class="btn btn-primary articles-load-more"
                    aria-describedby="load-more-description">
                Charger plus d'articles
            </button>
            <p id="load-more-description" class="visually-hidden">
                Cliquez pour charger les articles suivants
            </p>
        </div>
    @endif
</main>

<!-- Performance optimization: preload next page -->
@if(isset($nextPageUrl))
    <link rel="prefetch" href="{{ $nextPageUrl }}">
@endif

<!-- Accessibility: Skip to next section -->
<a href="#footer" class="skip-link">Passer au pied de page</a>