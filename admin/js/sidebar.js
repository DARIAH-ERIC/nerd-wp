( function( wp ) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginSidebar = wp.editPost.PluginSidebar;
    var el = wp.element.createElement;
    var Button = wp.components.Button;

    registerPlugin( 'nerd-plugin-sidebar', {
        render: function() {
            return el( PluginSidebar,
                {
                    name: 'nerd-plugin-sidebar',
                    icon: 'admin-post',
                    title: 'NERD WP',
                },
                el( 'div',
                    { className: 'nerd-plugin-sidebar-content' },
                    el( Button, {
                        isPrimary: true,
                        className: 'nerd-plugin-sidebar-btn',
                        onClick: function() {
                            let post_id = wp.data.select("core/editor").getCurrentPostId();
                            console.log( 'Button clicked for id: ' + post_id );
                            wp.apiRequest( { path: '/nerd-gutenberg/v1/relaunch-nerd?post_id=' + post_id, method: 'POST' } ).then(
                                ( data ) => {
                                    return data;
                                },
                                ( err ) => {
                                    return err;
                                }
                            );
                        },
                    }, 'Relaunch NERD' )
                )
            );
        }
    } );
} )( window.wp );
