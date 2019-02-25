# How does web analytics work?

The idea behind web or app analytics is to track how the behavior is on a website or app. You are able to see how your users
are using your website, with flow overviews, events, ecommerce, time on site and pageviews, 
if you setup your analytics correctly.

It doesn't matter which analytics tool you use, the way you send data to the system will be the same. You will hear about the
following analytics tools in the web or app analytics world:
- [Google Analytics](https://marketingplatform.google.com/about/analytics/): Is a free analytics tool developt by Google.
It will cost a lot of money if: 
you need more detailed reporting, you need more variables, more detailed flow reports, no sampling of data, faster processing
of data or access to unprocessed data. You can read about it [here](https://www.blastam.com/blog/what-is-google-analytics-premium)
- [Adobe Analytics](https://www.adobe.com/analytics/adobe-analytics.html): Is another analytics tool owned by Adobe. 
Its a more premium product than Google and dont have a freemium account.
- [Piwik or Matomo Analytics](https://matomo.org/): Is another solution which also can be free, if you install it and set it up
on your own servers. But Matomo will handle the servers and the responsibility for up time, if you pay them a little bit
pr month and package in pr pageview.
- etc.: There is a lot of other solutions but most of them work the same implementation wise.

## How to implement?

All the solutions need a JavaScript snippet, where the best solutions is loaded asynchronously (not blocking). Here is
an example of the analytics script for Google Analytics:

```html
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-12345678-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-12345678-1');
</script>
```

That code will load a script from Google's servers asyncronously and create a global scoped variable `dataLayer` which is an array,
and create a global scoped function called `gtag`. This code will fire, what they call a pageview request in the web analytics world.

