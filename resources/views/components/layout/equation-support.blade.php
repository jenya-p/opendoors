<?php setcookie('mjx.menu', 'renderer:SVG', ['path' => '/']); ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.6/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script>
    MathJax.Ajax.config.path["mhchem"] = "https://cdnjs.cloudflare.com/ajax/libs/mathjax-mhchem/3.3.2";
    MathJax.Hub.Config({
        showMathMenu: false,
        TeX: {
            extensions: [ "[mhchem]/mhchem.js" ]
        },
        messageStyle: "none",
        tex2jax: {
            preview: "none"
        },
        jax: ["input/TeX", "output/SVG"],
    });
</script>
