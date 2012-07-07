import urllib
import urllib2

from basesort import BaseSort


class HerokuSort(BaseSort):

    def exec_request(self):

        data = urllib.urlencode({'css': self.original})
        ua = 'Sublime Text 2 - CSScomb'
        req = urllib2.Request("http://csscomb.herokuapp.com/sort.php", data, headers={'User-Agent': ua})
        file = urllib2.urlopen(req, timeout=10)

        sorted_css = file.read()

        if len(sorted_css) > 0:
            return sorted_css
        else:
            return None
