# coding: utf-8

import sublime
import sublime_plugin
import subprocess

from os import path, name
# from .csscomb import LocalSort

__file__ = path.normpath(path.abspath(__file__))
__path__ = path.dirname(__file__)
libs_path = path.join(__path__, 'libs')
csscomb_path = path.join(libs_path, 'call_string.php')


# def to_unicode_or_bust(obj, encoding='utf-8'):
#     if isinstance(obj, basestring):
#         if not isinstance(obj, unicode):
#             obj = unicode(obj, encoding)
#     return obj


class CssSorter(sublime_plugin.TextCommand):
    """Base Sorter"""

    def __init__(self, view):
        self.view = view
        self.error = False
        self.startupinfo = None

        if name == 'nt':
            self.startupinfo = subprocess.STARTUPINFO()
            self.startupinfo.dwFlags |= subprocess.STARTF_USESHOWWINDOW
            self.startupinfo.wShowWindow = subprocess.SW_HIDE

    def run(self, edit):
        self.view.begin_edit(0, 'sort css with csscomb')
        selections = self.get_selections()

        selbody = self.view.substr(selections[0])
        # selbody = selbody.encode('utf-8')
        self.original = str(selbody)

        self.check_php_on_path()

        try:
            if not self.error:
                myprocess = subprocess.Popen(['php', csscomb_path, self.original], shell=False, stdout=subprocess.PIPE, startupinfo=self.startupinfo)
                (sout, serr) = myprocess.communicate()
                myprocess.wait()

            if len(sout) > 0:
                print(sout)
                self.result = sout
            else:
                self.result = None

        except (OSError):
            self.error = True
            self.result = 'Sorter Error'


        sel = selections[0]
        self.result = str( self.result, encoding='utf8' )

        if not self.error:
            self.view.replace(edit, sel, self.result)

        self.view.end_edit(edit)

    def get_selections(self):
        selections = self.view.sel()

        # check if the user has any actual selections
        has_selections = False
        for sel in selections:
            if sel.empty() == False:
                has_selections = True

        # if not, add the entire file as a selection
        if not has_selections:
            full_region = sublime.Region(0, self.view.size())
            selections.add(full_region)

        return selections

    def check_php_on_path(self):
        try:
            subprocess.call(['php', '-v'], shell=False, startupinfo=self.startupinfo)
        except (OSError):
            self.error = True
            self.result = 'Unable to use PHP. Make sure it is available in your PATH.'
