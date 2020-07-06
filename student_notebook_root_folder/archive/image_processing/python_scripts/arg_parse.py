import argparse

parser = argparse.ArgumentParser()
parser.add_argument("arg1", help = "input a string")
parser.add_argument("arg2", help = "input another string")

args = parser.parse_args()

print("first argument: {}\nsecond argument: {}".format(args.arg1, args.arg2))
