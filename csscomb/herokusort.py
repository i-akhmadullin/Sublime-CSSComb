import sublime
import sublime_plugin
import urllib
import urllib2

from basesort import BaseSort

class HerokuSort(BaseSort):

    def exec_request(self):

        data = urllib.urlencode({ 'css': self.original.encode('utf-8') })
        print data
        ua = 'Sublime Text 2 - CSScomb'
        req = urllib2.Request("http://csscomb.herokuapp.com/sort.php", data, headers = { 'User-Agent': ua })
        file = urllib2.urlopen(req, timeout=5)

        sorted_css = file.read()
        print sorted_css
        if len(sorted_css) > 0:
            return sorted_css
        else:
            return None