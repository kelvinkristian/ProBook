from time import time
import hashlib
import array
import hmac


def generate_TOTP(K, time_step, digit_count):
    C = convert_long_to_bytes(int(time() / time_step))
    test = str(int(time() / time_step)).encode('utf-8')
    K_bytes = convert_string_to_ascii_bytes(K)
    hash = hmac.new(key=K_bytes, msg=test, digestmod=hashlib.sha1).hexdigest()
    return truncate(hash)[:digit_count]


def truncate(sha512_hash):
    offset = int(sha512_hash[-1], 16) & 0xf
    truncated_bytes = int(sha512_hash[(offset * 2):(offset * 2) + 8], 16)
    return str(truncated_bytes & 0x7fffffff)


def convert_long_to_bytes(long_numb):
    byte_list = []
    for i in range(8, 0, -1):
        byte_list.append(long_numb & 0xff)
        long_numb = long_numb >> 8
    return bytes(byte_list[::-1])


def convert_string_to_ascii_bytes(string):
    ascii_list = [ord(c) for c in string]
    return bytes(ascii_list)


if __name__ == "__main__":
    card_num = input("Enter your card number :")
    print(generate_TOTP(card_num, 30, 6))