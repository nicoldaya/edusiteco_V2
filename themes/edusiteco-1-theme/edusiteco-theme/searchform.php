<form role="search" aria-label="<?php echo esc_attr__('Buscar en el sitio', 'edusiteco'); ?>" method="get" class="search-form flex items-center gap-2" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search"
        class="search-field min-w-0 flex-1 bg-gray-100 dark:bg-gray-700 text-text-light dark:text-text-dark focus:ring-brand-primary-500 focus:border-primary block w-full pl-4 sm:text-sm border-gray-200 dark:border-gray-600 rounded-full py-2"
        placeholder="<?php echo esc_attr__('Buscar...', 'edusiteco'); ?>"
        value="<?php echo esc_attr(get_search_query()); ?>"
        name="s"
        aria-label="<?php echo esc_attr__('Término de búsqueda', 'edusiteco'); ?>"
        required />
    <button
        type="submit"
        class="search-submit flex flex-shrink-0 cursor-pointer items-center justify-center rounded-full bg-brand-primary-600 p-2 text-white transition-colors hover:bg-brand-primary-700 focus:outline-none focus:ring-2 focus:ring-brand-primary-500 focus:ring-offset-2"
        aria-label="<?php echo esc_attr__('Buscar', 'edusiteco'); ?>"
        title="<?php echo esc_attr__('Buscar', 'edusiteco'); ?>"
    >
        <svg class="pointer-events-none h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M11 6C13.7614 6 16 8.23858 16 11M16.6588 16.6549L21 21M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
        </svg>
    </button>
</form>