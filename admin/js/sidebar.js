( function( wp ) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginSidebar = wp.editPost.PluginSidebar;
    var PluginSidebarMoreMenuItem = wp.editPost.PluginSidebarMoreMenuItem;
    var Fragment = wp.element.Fragment;
    var el = wp.element.createElement;
    var Button = wp.components.Button;

    registerPlugin( 'nerd-plugin-sidebar', {
        render: function() {
            return el( Fragment, {},
                el( PluginSidebarMoreMenuItem,
                    {
                        target: 'nerd-plugin-sidebar',
                        icon: 'dashboard',
                    },
                    'NERD WP'
                ),
                el( PluginSidebar,
                    {
                        name: 'nerd-plugin-sidebar',
                        icon: 'dashboard',
                        title: 'NERD WP',
                    },
                    el( 'div',
                        { className: 'nerd-plugin-sidebar-content' },
                        el( Button, {
                            isPrimary: true,
                            className: 'nerd-plugin-sidebar-btn',
                            onClick: function() {
                                $(".nerd-plugin-sidebar-btn").addClass("is-busy");
                                let post_id = wp.data.select("core/editor").getCurrentPostId();
                                wp.apiRequest( { path: '/nerd-gutenberg/v1/relaunch-nerd?post_id=' + post_id, method: 'POST' } ).then(
                                    ( data ) => {
                                        // wp.data.select( 'core' ).getEntityRecords( 'taxonomy', 'post_tag', { post_per_page: -1 } );
                                        location.reload();
                                        return data;
                                    },
                                    ( err ) => {
                                        return err;
                                    }
                                );
                            },
                        }, 'Relaunch NERD' )
                    )
                )
            );
        }
    } );
} )( window.wp );
